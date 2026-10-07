<?php
$pageTitle = $pageTitle ?? 'Campus Locator';
$activePage = $activePage ?? '';
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="theme-color" content="#f6f9ff">
  <meta name="description" content="Find faculty, campus departments, rooms and facilities at MapMyUni, Delhi NCR.">
  <title><?= h($pageTitle) ?> | MapMyUni – Campus Locator</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
  <script src="app.js" defer></script>
</head>
<body>
  <a class="skip-link" href="#main-content">Skip to main content</a>
  <header class="site-header">
    <div class="header-inner">
      <a class="brand" href="index.php" aria-label="MapMyUni Campus Locator home">
        <span class="brand-mark" aria-hidden="true">M</span>
        <span class="brand-copy"><strong>MapMyUni</strong><small>Campus Locator</small></span>
      </a>
      <div class="header-actions">
        <nav class="primary-nav" id="primaryNav" aria-label="Main navigation">
          <a href="index.php" <?= $activePage === 'home' ? 'aria-current="page"' : '' ?>>Home</a>
          <a href="discover.php" <?= $activePage === 'discover' ? 'aria-current="page"' : '' ?>>Discover</a>
          <a href="gallery.php" <?= $activePage === 'gallery' ? 'aria-current="page"' : '' ?>>Gallery</a>
          <a href="contact.php" <?= $activePage === 'contact' ? 'aria-current="page"' : '' ?>>Contact</a>
        </nav>
        <button class="icon-button theme-toggle" type="button" data-theme-toggle aria-label="Switch to dark mode" aria-pressed="false">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20.9 13A8.9 8.9 0 0 1 11 3.1 9 9 0 1 0 20.9 13Z"/></svg>
        </button>
        <button class="menu-button" type="button" data-menu-toggle aria-controls="primaryNav" aria-expanded="false" aria-label="Open navigation menu">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" aria-hidden="true"><path d="M4 7h16M4 12h16M4 17h16"/></svg>
        </button>
      </div>
    </div>
  </header>
