<?php
require_once __DIR__ . '/includes/bootstrap.php';

$pageTitle = 'Home';
$activePage = 'home';
$databaseReady = false;
$facultyCount = 0;
$departmentCount = 0;
$locationCount = 0;
$featuredFaculty = [];

try {
    $pdo = db();
    $databaseReady = true;
    $facultyCount = (int)$pdo->query('SELECT COUNT(*) FROM faculty')->fetchColumn();
    $departmentCount = (int)$pdo->query("SELECT COUNT(DISTINCT department) FROM faculty WHERE department <> ''")->fetchColumn();
    $locationCount = (int)$pdo->query("SELECT COUNT(*) FROM faculty WHERE location <> ''")->fetchColumn();
    $featuredFaculty = $pdo->query(
        "SELECT name, email, department, location FROM faculty ORDER BY name ASC LIMIT 6"
    )->fetchAll();
} catch (Throwable $error) {
    $databaseReady = false;
    error_log('Campus Locator home page database error: ' . $error->getMessage());
}

require __DIR__ . '/includes/header.php';
?>
<main id="main-content">

  <!-- MAPMYUNI HERO -->
  <section class="home-hero" aria-labelledby="hero-title">
    <video class="hero-video" autoplay muted loop playsinline poster="assets/gallery/1.jpg" aria-hidden="true">
      <source src="assets/media/campus-banner.mp4" type="video/mp4">
    </video>

    <div class="hero-shade"></div>

    <div class="container hero-content">

      <p class="hero-kicker">
        <span></span>
        MAPMYUNI · CAMPUS NAVIGATION PLATFORM
      </p>

      <h1 id="hero-title">
        Your campus.<br>
        Easier to navigate.
      </h1>

      <p class="hero-lead">
        MapMyUni helps you find people, places, rooms and facilities
        across the CHRIST (Deemed to be University) | Delhi NCR Campus.
      </p>

      <form class="hero-search" action="campus.php" method="get" role="search">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
          stroke-width="1.8" stroke-linecap="round" aria-hidden="true">
          <circle cx="11" cy="11" r="7"/>
          <path d="m20 20-4-4"/>
        </svg>

        <input
          type="search"
          name="q"
          placeholder="Search a person, room, facility or department"
          aria-label="Search MapMyUni campus directory"
        >

        <button class="button button-gold" type="submit">
          Explore MapMyUni
          <span aria-hidden="true">→</span>
        </button>
      </form>

      <div class="hero-meta">
        <span class="live-dot"></span>
        MapMyUni Directory
        <span class="meta-separator">·</span>
        People · Places · Campus Life
      </div>

    </div>

    <div class="hero-scroll" aria-hidden="true">
      <span></span>
      Explore MapMyUni
    </div>
  </section>


  <!-- CAMPUS CONTEXT -->
  <div class="video-credit">
    ▣ &nbsp; Campus media · CHRIST (Deemed to be University), Delhi NCR
  </div>


  <!-- ABOUT MAPMYUNI -->
  <section class="welcome-section container">

    <div class="welcome-copy">
      <p class="eyebrow">What is MapMyUni?</p>

      <h2>
        One platform.<br>
        <span>Everything on campus.</span>
      </h2>
    </div>

    <div class="welcome-text">

      <p>
        MapMyUni is a campus navigation and information platform designed
        to make everyday university life easier. Find faculty, departments,
        rooms, facilities and important campus spaces from one place.
      </p>

      <p>
        Currently serving the
        <strong>CHRIST (Deemed to be University), Delhi NCR campus.</strong>
      </p>

      <a class="text-link" href="discover.php">
        Start exploring MapMyUni
        <span aria-hidden="true">→</span>
      </a>

    </div>

  </section>


  <?php if (!$databaseReady): ?>

    <section class="container setup-banner" role="status">
      <div>
        <strong>MapMyUni directory setup</strong>
        <p><?= h(database_setup_notice()) ?></p>
      </div>

      <a class="button button-blue" href="discover.php">
        Explore MapMyUni
      </a>
    </section>

  <?php else: ?>

    <section class="stats-strip container" aria-label="MapMyUni directory summary">

      <div class="stat-item">
        <strong><?= number_format($facultyCount) ?></strong>
        <span>faculty &amp; staff</span>
      </div>

      <div class="stat-item">
        <strong><?= number_format($departmentCount) ?></strong>
        <span>departments &amp; teams</span>
      </div>

      <div class="stat-item">
        <strong><?= number_format($locationCount) ?></strong>
        <span>campus locations</span>
      </div>

      <div class="stat-caption">
        MapMyUni brings useful campus information together in one place.
      </div>

    </section>

  <?php endif; ?>


  <!-- MAPMYUNI FEATURES -->
  <section class="section container">

    <div class="section-heading">

      <div>
        <p class="eyebrow">Explore MapMyUni</p>

        <h2>
          Find what you need,
          faster.
        </h2>

        <p>
          Navigate the campus through people, places and campus life.
        </p>
      </div>

      <a class="text-link desktop-only" href="discover.php">
        View all features
        <span aria-hidden="true">→</span>
      </a>

    </div>


    <div class="highlight-grid">

      <!-- PEOPLE -->
      <a class="highlight-card faculty-highlight" href="faculty.php">

        <span class="highlight-icon">
          <svg viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="1.8"
            stroke-linecap="round"
            stroke-linejoin="round"
            aria-hidden="true">

            <circle cx="9" cy="8" r="4"/>
            <path d="M2 21v-2a7 7 0 0 1 14 0v2"/>
            <path d="m18 11 2 2 3-4"/>

          </svg>
        </span>

        <span class="highlight-label">
          PEOPLE
        </span>

        <h3>
          Faculty &amp; Departments
        </h3>

        <p>
          Find faculty and staff by name, department,
          email or office location.
        </p>

        <span class="card-arrow" aria-hidden="true">
          ↗
        </span>

      </a>


      <!-- PLACES -->
      <a class="highlight-card campus-highlight" href="campus.php">

        <span class="highlight-icon">
          <svg viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="1.8"
            stroke-linecap="round"
            stroke-linejoin="round"
            aria-hidden="true">

            <path d="M3 21h18"/>
            <path d="M5 21V7l7-4 7 4v14"/>
            <path d="M9 10h.01"/>
            <path d="M15 10h.01"/>
            <path d="M9 14h.01"/>
            <path d="M15 14h.01"/>
            <path d="M10 21v-4h4v4"/>

          </svg>
        </span>

        <span class="highlight-label">
          PLACES
        </span>

        <h3>
          Campus Navigator
        </h3>

        <p>
          Search classrooms, offices, auditoriums,
          labs and other campus facilities.
        </p>

        <span class="card-arrow" aria-hidden="true">
          ↗
        </span>

      </a>


      <!-- CAMPUS LIFE -->
      <a class="highlight-card gallery-highlight" href="gallery.php">

        <span class="highlight-icon">
          <svg viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="1.8"
            stroke-linecap="round"
            stroke-linejoin="round"
            aria-hidden="true">

            <rect x="3" y="3" width="18" height="18" rx="3"/>
            <circle cx="8.5" cy="8.5" r="1.5"/>
            <path d="m21 15-5-5L5 21"/>

          </svg>
        </span>

        <span class="highlight-label">
          CAMPUS LIFE
        </span>

        <h3>
          Explore Campus
        </h3>

        <p>
          Discover the spaces, events and everyday
          moments that make the campus yours.
        </p>

        <span class="card-arrow" aria-hidden="true">
          ↗
        </span>

      </a>

    </div>

  </section>


  <?php if ($databaseReady && $featuredFaculty !== []): ?>

    <!-- FEATURED DIRECTORY -->
    <section class="section featured-section">

      <div class="container">

        <div class="section-heading">

          <div>
            <p class="eyebrow">
              MapMyUni Directory
            </p>

            <h2>
              Find the people behind the campus.
            </h2>

            <p>
              Search the complete faculty directory by name,
              department or location.
            </p>
          </div>

          <a class="text-link" href="faculty.php">
            Open faculty directory
            <span aria-hidden="true">→</span>
          </a>

        </div>


        <div class="people-grid">

          <?php foreach ($featuredFaculty as $person): ?>

            <article class="person-card">

              <div class="person-avatar" aria-hidden="true">
                <?= h(strtoupper(substr($person['name'], 0, 1))) ?>
              </div>

              <div class="person-info">

                <h3>
                  <?= h($person['name']) ?>
                </h3>

                <p>
                  <?= h($person['department']) ?>
                </p>

                <?php if (trim((string)$person['location']) !== ''): ?>

                  <span class="person-location">
                    <?= h($person['location']) ?>
                  </span>

                <?php endif; ?>

              </div>

            </article>

          <?php endforeach; ?>

        </div>

      </div>

    </section>

  <?php endif; ?>


  <!-- CAMPUS IDENTITY -->
  <section class="section container mapmyuni-campus-note">

    <div class="mapmyuni-campus-note-inner">

      <div>
        <p class="eyebrow">
          Powered by MapMyUni
        </p>

        <h2>
          Built for campus navigation.
        </h2>

        <p>
          MapMyUni is the digital layer that connects
          students, faculty and campus spaces through
          one simple directory.
        </p>
      </div>

      <div class="campus-badge">

        <strong>
          CHRIST
        </strong>

        <span>
          (Deemed to be University)
        </span>

        <small>
          Delhi NCR Campus
        </small>

      </div>

    </div>

  </section>

</main>

<?php require __DIR__ . '/includes/footer.php'; ?>