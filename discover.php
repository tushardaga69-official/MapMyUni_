<?php
require_once __DIR__ . '/includes/bootstrap.php';
$pageTitle = 'Discover Campus & Faculty';
$activePage = 'discover';
require __DIR__ . '/includes/header.php';
?>
<main id="main-content">

  <section class="container page-hero">

    <p class="eyebrow">
      MapMyUni Directory
    </p>

    <h1>
      Explore your campus.
    </h1>

    <p>
      MapMyUni brings the people and places of the
      CHRIST (Deemed to be University), Delhi NCR campus
      together in one searchable platform.
    </p>

  </section>


  <section class="discover-grid" aria-label="MapMyUni navigation options">

    <!-- FACULTY -->
    <a class="discover-option" href="faculty.php">

      <span class="option-icon">

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

      <h2>
        Find Faculty
      </h2>

      <p>
        Search faculty and staff across departments
        using names, email addresses, departments
        or office locations.
      </p>

      <span class="text-link">
        Open faculty directory
        <span aria-hidden="true">→</span>
      </span>

    </a>


    <!-- CAMPUS -->
    <a class="discover-option" href="campus.php">

      <span class="option-icon">

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

      <h2>
        Navigate Campus
      </h2>

      <p>
        Find classrooms, offices, labs, auditoriums
        and other important campus facilities.
      </p>

      <span class="text-link">
        Open campus navigator
        <span aria-hidden="true">→</span>
      </span>

    </a>

  </section>


  <section class="container mapmyuni-campus-context">

    <p class="eyebrow">
      Currently serving
    </p>

    <h2>
      CHRIST (Deemed to be University)
    </h2>

    <p>
      Delhi NCR Campus · Ghaziabad
    </p>

  </section>

</main>

<?php require __DIR__ . '/includes/footer.php'; ?>