<?php
require_once __DIR__ . '/includes/bootstrap.php';

$pageTitle = 'Faculty Directory';
$activePage = 'discover';
$query = trim((string)($_GET['q'] ?? ''));
$query = mb_substr($query, 0, 100);
$selectedDepartment = trim((string)($_GET['department'] ?? ''));
$pageNumber = max(1, (int)($_GET['page'] ?? 1));
$pageSize = 24;
$departments = [];
$people = [];
$total = 0;
$databaseError = false;

try {
    $pdo = db();
    $departments = $pdo->query("SELECT DISTINCT department FROM faculty WHERE department <> '' ORDER BY department")->fetchAll(PDO::FETCH_COLUMN);
    $conditions = [];
    $parameters = [];
    if ($query !== '') {
        $conditions[] = '(name LIKE :name_term OR email LIKE :email_term OR department LIKE :department_term OR location LIKE :location_term)';
        $likeTerm = '%' . $query . '%';
        $parameters[':name_term'] = $likeTerm;
        $parameters[':email_term'] = $likeTerm;
        $parameters[':department_term'] = $likeTerm;
        $parameters[':location_term'] = $likeTerm;
    }
    if ($selectedDepartment !== '') {
        $conditions[] = 'department = :department';
        $parameters[':department'] = $selectedDepartment;
    }
    $where = $conditions ? ' WHERE ' . implode(' AND ', $conditions) : '';
    $countStatement = $pdo->prepare('SELECT COUNT(*) FROM faculty' . $where);
    $countStatement->execute($parameters);
    $total = (int)$countStatement->fetchColumn();
    $lastPage = max(1, (int)ceil($total / $pageSize));
    $pageNumber = min($pageNumber, $lastPage);
    $offset = ($pageNumber - 1) * $pageSize;
    $statement = $pdo->prepare(
        'SELECT name, email, department, location FROM faculty' . $where .
        ' ORDER BY name ASC LIMIT :limit OFFSET :offset'
    );
    foreach ($parameters as $key => $value) {
        $statement->bindValue($key, $value, PDO::PARAM_STR);
    }
    $statement->bindValue(':limit', $pageSize, PDO::PARAM_INT);
    $statement->bindValue(':offset', $offset, PDO::PARAM_INT);
    $statement->execute();
    $people = $statement->fetchAll();
} catch (Throwable $error) {
    $databaseError = true;
    error_log('Campus Locator faculty page database error: ' . $error->getMessage());
}

require __DIR__ . '/includes/header.php';
?>
<main id="main-content" class="container">
  <section class="page-hero">
    <p class="eyebrow">People &amp; departments</p>
    <h1>Faculty directory</h1>
    <p>Search faculty and staff by name, department, email or office location.</p>
  </section>

  <?php if ($databaseError): ?>
    <section class="empty-state" role="status"><strong>Faculty data is not connected yet.</strong><p><?= h(database_setup_notice()) ?></p></section>
  <?php else: ?>
    <form class="search-panel" action="faculty.php" method="get" role="search">
      <label class="input-wrap"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="m20 20-4-4"/></svg><input class="form-control" type="search" name="q" value="<?= h($query) ?>" placeholder="Search name, department, email or room" aria-label="Search faculty directory"></label>
      <label><span class="skip-link">Filter by department</span><select class="form-control" name="department" aria-label="Filter by department"><option value="">All departments</option><?php foreach ($departments as $department): ?><option value="<?= h((string)$department) ?>" <?= $selectedDepartment === $department ? 'selected' : '' ?>><?= h((string)$department) ?></option><?php endforeach; ?></select></label>
      <button class="button button-blue" type="submit">Search <span aria-hidden="true">→</span></button>
    </form>

    <div class="result-bar"><span role="status" aria-live="polite"><?php if ($query !== '' || $selectedDepartment !== ''): ?><?= number_format($total) ?> matching entries<?php else: ?><?= number_format($total) ?> directory entries<?php endif; ?></span><a class="text-link" href="faculty.php">Clear filters</a></div>
    <section class="results-grid" aria-label="Faculty directory results">
      <?php if ($people === []): ?>
        <div class="empty-state"><strong><?= $total === 0 ? 'No matching faculty found' : 'No faculty records yet' ?></strong><p><?= $total === 0 ? 'Try a different name, room code or department.' : 'Import your CSV data using the XAMPP setup steps in README.md.' ?></p></div>
      <?php else: ?>
        <?php foreach ($people as $person): ?>
          <article class="result-card">
            <span class="result-tag"><?= h($person['department'] ?: 'Department not listed') ?></span>
            <h2><?= h($person['name']) ?></h2>
            <?php if (filter_var($person['email'], FILTER_VALIDATE_EMAIL)): ?><p><a href="mailto:<?= h($person['email']) ?>"><?= h($person['email']) ?></a></p><?php else: ?><p>Email not listed</p><?php endif; ?>
            <?php if (trim((string)$person['location']) !== ''): ?><p class="result-location"><span aria-hidden="true">⌖</span><?= h($person['location']) ?></p><?php else: ?><p class="result-location">Office location not listed</p><?php endif; ?>
          </article>
        <?php endforeach; ?>
      <?php endif; ?>
    </section>
    <?php if ($total > $pageSize): ?>
      <nav class="pagination" aria-label="Search result pages">
        <?php if ($pageNumber > 1): ?><a class="button button-outline" href="?<?= h(http_build_query(['q' => $query, 'department' => $selectedDepartment, 'page' => $pageNumber - 1])) ?>">← Previous</a><?php endif; ?>
        <span>Page <?= number_format($pageNumber) ?> of <?= number_format($lastPage) ?></span>
        <?php if ($pageNumber < $lastPage): ?><a class="button button-outline" href="?<?= h(http_build_query(['q' => $query, 'department' => $selectedDepartment, 'page' => $pageNumber + 1])) ?>">Next →</a><?php endif; ?>
      </nav>
    <?php endif; ?>
  <?php endif; ?>
</main>
<?php require __DIR__ . '/includes/footer.php'; ?>
