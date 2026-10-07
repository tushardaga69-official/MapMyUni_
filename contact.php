<?php
require_once __DIR__ . '/includes/bootstrap.php';

$pageTitle = 'Contact';
$activePage = 'contact';
$topic = 'General enquiry';
$messageStatus = (string)($_SESSION['contact_flash'] ?? '');
$messageStatusType = $messageStatus !== '' ? 'success' : '';
unset($_SESSION['contact_flash']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim((string)($_POST['name'] ?? ''));
    $email = trim((string)($_POST['email'] ?? ''));
    $topic = trim((string)($_POST['topic'] ?? 'General enquiry'));
    $message = trim((string)($_POST['message'] ?? ''));
    $honeypot = trim((string)($_POST['company_website'] ?? ''));

    if (!csrf_is_valid($_POST['csrf_token'] ?? null)) {
        $messageStatus = 'Your form session expired. Refresh the page and try again.';
        $messageStatusType = 'error';
    } elseif ($honeypot !== '') {
        $messageStatus = 'Thank you. Your message has been recorded.';
        $messageStatusType = 'success';
    } elseif ($name === '' || mb_strlen($name) > 120 || !filter_var($email, FILTER_VALIDATE_EMAIL)
        || mb_strlen($email) > 190 || $message === '' || mb_strlen($message) > 5000) {
        $messageStatus = 'Please enter your name, a valid email address and a message under 5,000 characters.';
        $messageStatusType = 'error';
    } else {
        try {
            $statement = db()->prepare('INSERT INTO contact_messages (name, email, topic, message) VALUES (:name, :email, :topic, :message)');
            $statement->execute([
                ':name' => $name,
                ':email' => $email,
                ':topic' => mb_substr($topic ?: 'General enquiry', 0, 100),
                ':message' => $message,
            ]);
            $_SESSION['contact_flash'] = 'Your message has been saved. The campus team can follow up using the email address you provided.';
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            header('Location: contact.php', true, 303);
            exit;
        } catch (Throwable $error) {
            error_log('Campus Locator contact form database error: ' . $error->getMessage());
            $messageStatus = 'We could not save your message just now. Please use one of the campus email or phone contacts.';
            $messageStatusType = 'error';
        }
    }
}

$contactStorageReady = database_is_ready();
require __DIR__ . '/includes/header.php';
?>
<main id="main-content" class="container">
  <section class="contact-intro">
    <p class="eyebrow">We’re here to help</p>
    <h1>Contact Map My Uni</h1>
    <p>Questions about the directory, a listing that needs correcting, or campus facilities? Send a message to the Map My Uni team or use the campus contacts below.</p>
  </section>

  <section class="contact-layout" aria-label="Campus contact information and message form">
    <div class="contact-stack">
      <article class="contact-card">
        <h2><span aria-hidden="true">⌖</span> Campus address</h2>
        <p>Delhi NCR Campus<br>Mariam Nagar, Meerut Road<br>Ghaziabad – 201003<br><a href="https://maps.google.com/?q=Mariam+Nagar+Meerut+Road+Ghaziabad+201003" target="_blank" rel="noopener noreferrer">Open campus location ↗</a></p>
      </article>
      <article class="contact-card">
        <h2><span aria-hidden="true">✉</span> Campus helpdesk</h2>
        <p>Email: <a href="mailto:mail.ncr@christuniversity.in">mail.ncr@christuniversity.in</a><br>Toll-free: <a href="tel:18001233212">1800-123-3212</a><br>Phone: <a href="tel:01206666100">0120-6666100</a></p>
      </article>
      <article class="contact-card">
        <h2><span aria-hidden="true">✉</span> Campus administration</h2>
        <p>Email: <a href="mailto:office.ncr@christuniversity.in">office.ncr@christuniversity.in</a><br>Phone: <a href="tel:01202988000">0120-2988000</a></p>
      </article>
      <article class="contact-card">
        <h2><span aria-hidden="true">↗</span> Map My Uni feedback</h2>
        <p>For feedback or corrections to this directory, email <a href="mailto:tushar.daga@bcah.christuniversity.in">tushar.daga@bcah.christuniversity.in</a>.</p>
      </article>
    </div>

    <form class="contact-form" action="contact.php" method="post">
      <h2>Send a message</h2>
      <?php if ($contactStorageReady): ?><p>Your message will be saved in the campus database for follow-up.</p><?php else: ?><p class="status-error">Message storage needs XAMPP setup before this form can be used. See README.md for the database steps.</p><?php endif; ?>
      <input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>">
      <div class="honeypot" aria-hidden="true"><label for="company_website">Leave this field empty</label><input id="company_website" name="company_website" type="text" tabindex="-1" autocomplete="off"></div>
      <div class="form-grid">
        <div class="form-field"><label for="name">Name</label><input class="form-control" id="name" name="name" type="text" autocomplete="name" maxlength="120" required value="<?= h($_POST['name'] ?? '') ?>"></div>
        <div class="form-field"><label for="email">Email</label><input class="form-control" id="email" name="email" type="email" autocomplete="email" maxlength="190" required value="<?= h($_POST['email'] ?? '') ?>"></div>
        <div class="form-field full"><label for="topic">Topic</label><select class="form-control" id="topic" name="topic"><?php foreach (['General enquiry', 'Admissions', 'Academics', 'Campus facilities', 'Website feedback'] as $topicOption): ?><option value="<?= h($topicOption) ?>" <?= $topic === $topicOption ? 'selected' : '' ?>><?= h($topicOption) ?></option><?php endforeach; ?></select></div>
        <div class="form-field full"><label for="message">Message</label><textarea class="form-control" id="message" name="message" maxlength="5000" required><?= h($_POST['message'] ?? '') ?></textarea></div>
      </div>
      <div class="form-actions"><button class="button button-blue" type="submit">Submit message <span aria-hidden="true">→</span></button><small>Fields marked by the browser are required.</small></div>
      <?php if ($messageStatus !== ''): ?><p class="form-status <?= $messageStatusType === 'error' ? 'status-error' : '' ?>" role="status" aria-live="polite"><?= h($messageStatus) ?></p><?php endif; ?>
    </form>
  </section>
</main>
<?php require __DIR__ . '/includes/footer.php'; ?>
