<?php
require_once __DIR__ . '/includes/bootstrap.php';
$pageTitle = 'Campus Gallery';
$activePage = 'gallery';
$galleryFiles = glob(__DIR__ . '/assets/gallery/*.{jpg,jpeg,png,webp}', GLOB_BRACE) ?: [];
natsort($galleryFiles);
$galleryFiles = array_values($galleryFiles);
require __DIR__ . '/includes/header.php';
?>
<main id="main-content" class="container">
  <section class="gallery-intro">
    <p class="eyebrow">Life around campus</p>
    <h1>Campus gallery</h1>
    <p>From student exhibitions to everyday campus moments, take a look at life around the Map My Uni campus.</p>
  </section>
  <?php if ($galleryFiles === []): ?>
    <section class="empty-state" role="status"><strong>Campus photos are not installed yet</strong><p>Add the campus photographs to <code>assets/gallery/</code> to show them here.</p></section>
  <?php else: ?>
    <section class="gallery-grid" aria-label="Map My Uni campus photographs">
      <?php foreach ($galleryFiles as $index => $file): $relativePath = 'assets/gallery/' . basename($file); ?>
        <button class="gallery-tile" type="button" data-lightbox-src="<?= h($relativePath) ?>" data-lightbox-alt="Map My Uni campus life, photograph <?= $index + 1 ?>" aria-label="Open campus photograph <?= $index + 1 ?>">
          <img src="<?= h($relativePath) ?>" alt="Map My Uni campus life, photograph <?= $index + 1 ?>" loading="lazy">
          <span>Campus life · <?= str_pad((string)($index + 1), 2, '0', STR_PAD_LEFT) ?></span>
        </button>
      <?php endforeach; ?>
    </section>
  <?php endif; ?>
</main>
<dialog class="gallery-lightbox" id="galleryLightbox" aria-label="Campus photo viewer">
  <button class="lightbox-close" type="button" data-lightbox-close aria-label="Close photo">×</button>
  <img src="" alt="" data-lightbox-image>
  <div class="lightbox-caption" data-lightbox-caption></div>
</dialog>
<?php require __DIR__ . '/includes/footer.php'; ?>
