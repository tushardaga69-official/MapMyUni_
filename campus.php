<?php
require_once __DIR__ . '/includes/bootstrap.php';

$pageTitle = 'Campus Facilities & Rooms';
$activePage = 'discover';
$query = trim((string)($_GET['q'] ?? ''));
$query = mb_substr($query, 0, 100);
$block = strtoupper(trim((string)($_GET['block'] ?? '')));
if (!in_array($block, ['', 'A', 'B'], true)) {
    $block = '';
}
$places = [];
$facultyRooms = [];
$databaseError = false;

try {
    $pdo = db();
    $placeConditions = [];
    $placeParameters = [];
    if ($query !== '') {
        $placeConditions[] = '(name LIKE :place_name OR floor LIKE :place_floor OR room_code LIKE :place_room)';
        $placeParameters[':place_name'] = '%' . $query . '%';
        $placeParameters[':place_floor'] = '%' . $query . '%';
        $placeParameters[':place_room'] = '%' . $query . '%';
    }
    if ($block !== '') {
        $placeConditions[] = 'block = :place_block';
        $placeParameters[':place_block'] = $block;
    }
    $placeWhere = $placeConditions ? ' WHERE ' . implode(' AND ', $placeConditions) : '';
    $placeStatement = $pdo->prepare('SELECT name, block, floor, room_code FROM campus_places' . $placeWhere . ' ORDER BY name LIMIT 500');
    $placeStatement->execute($placeParameters);
    $places = $placeStatement->fetchAll();

    if ($query !== '' || $block !== '') {
        $facultyConditions = ["location <> ''"];
        $facultyParameters = [];
        if ($query !== '') {
            $facultyConditions[] = '(name LIKE :person_name OR email LIKE :person_email OR department LIKE :person_department OR location LIKE :person_location)';
            $facultyParameters[':person_name'] = '%' . $query . '%';
            $facultyParameters[':person_email'] = '%' . $query . '%';
            $facultyParameters[':person_department'] = '%' . $query . '%';
            $facultyParameters[':person_location'] = '%' . $query . '%';
        }
        if ($block !== '') {
            $facultyConditions[] = 'UPPER(location) LIKE :person_block';
            $facultyParameters[':person_block'] = '%' . $block . '%BLOCK%';
        }
        $facultyWhere = ' WHERE ' . implode(' AND ', $facultyConditions);
        $facultyStatement = $pdo->prepare('SELECT name, email, department, location FROM faculty' . $facultyWhere . ' ORDER BY name LIMIT 500');
        $facultyStatement->execute($facultyParameters);
        $facultyRooms = $facultyStatement->fetchAll();
    }
} catch (Throwable $error) {
    $databaseError = true;
    error_log('Campus Locator place page database error: ' . $error->getMessage());
}

require __DIR__ . '/includes/header.php';
?>
<main id="main-content" class="container">
  <section class="page-hero">
    <p class="eyebrow">Rooms, offices &amp; shared spaces</p>
    <h1>Navigate campus</h1>
    <p>Find a room or facility by name, room code, floor or block. Faculty office locations appear alongside campus places.</p>
  </section>

  <section class="map-overview" aria-label="Campus block directory illustration">
    <a class="map-block" href="?block=A"><span class="map-letter">A</span><span><strong>A Block</strong><small>Browse listed locations</small></span></a>
    <span class="map-path" aria-hidden="true"></span>
    <a class="map-block" href="?block=B"><span class="map-letter">B</span><span><strong>B Block</strong><small>Browse listed locations</small></span></a>
    <p class="map-caption">Block overview for navigation only · not a scale campus map</p>
  </section>

  <form class="search-panel" action="campus.php" method="get" role="search">
    <label class="input-wrap"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="m20 20-4-4"/></svg><input class="form-control" type="search" name="q" value="<?= h($query) ?>" placeholder="Search a room, facility or faculty name" aria-label="Search campus locations"></label>
    <label><span class="skip-link">Filter by block</span><select class="form-control" name="block" aria-label="Filter by block"><option value="" <?= $block === '' ? 'selected' : '' ?>>All blocks</option><option value="A" <?= $block === 'A' ? 'selected' : '' ?>>A Block</option><option value="B" <?= $block === 'B' ? 'selected' : '' ?>>B Block</option></select></label>
    <button class="button button-blue" type="submit">Find a place <span aria-hidden="true">→</span></button>
  </form>

  <?php if ($databaseError): ?>
    <section class="empty-state" role="status"><strong>Campus data is not connected yet.</strong><p><?= h(database_setup_notice()) ?></p></section>
  <?php else: ?>
    <div class="block-filter" aria-label="Quick block filters"><a class="filter-chip <?= $block === '' ? 'active' : '' ?>" href="campus.php<?= $query !== '' ? '?q=' . rawurlencode($query) : '' ?>">All locations</a><a class="filter-chip <?= $block === 'A' ? 'active' : '' ?>" href="?<?= h(http_build_query(['q' => $query, 'block' => 'A'])) ?>">A Block</a><a class="filter-chip <?= $block === 'B' ? 'active' : '' ?>" href="?<?= h(http_build_query(['q' => $query, 'block' => 'B'])) ?>">B Block</a></div>

    <div class="result-bar"><span role="status" aria-live="polite"><?= number_format(count($places) + count($facultyRooms)) ?> locations shown</span><?php if ($query !== '' || $block !== ''): ?><a class="text-link" href="campus.php">Clear filters</a><?php else: ?><span>Search to include faculty office rooms</span><?php endif; ?></div>

    <section class="section-results" aria-labelledby="place-heading">
      <div class="section-heading"><div><p class="eyebrow">Shared spaces</p><h2 id="place-heading">Campus facilities</h2></div></div>
      <div class="results-grid">
        <?php if ($places === []): ?><div class="empty-state"><strong>No campus places match this search</strong><p>Try a shorter place name, floor or room code.</p></div><?php else: ?>
          <?php foreach ($places as $place): ?>
            <article class="result-card"><span class="result-tag">Campus place<?= $place['block'] !== '' ? ' · Block ' . h($place['block']) : '' ?></span><h2><?= h($place['name']) ?></h2>
              <?php if ($place['floor'] !== '' || $place['room_code'] !== ''): ?><p class="result-location"><span aria-hidden="true">⌖</span><?= h(trim($place['room_code'] . ' ' . $place['floor'])) ?></p><?php else: ?><p class="result-location">Block <?= h($place['block']) ?> · Room details not listed</p><?php endif; ?>
            </article>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </section>

    <?php if ($query !== '' || $block !== ''): ?>
      <section class="section-results staff-rooms" aria-labelledby="staff-room-heading">
        <div class="section-heading"><div><p class="eyebrow">Faculty &amp; staff</p><h2 id="staff-room-heading">Office locations</h2></div></div>
        <div class="results-grid">
          <?php if ($facultyRooms === []): ?><div class="empty-state"><strong>No staff offices match this search</strong><p>Search by a person’s name, department or room code.</p></div><?php else: ?>
            <?php foreach ($facultyRooms as $person): ?>
              <article class="result-card"><span class="result-tag"><?= h($person['department'] ?: 'Faculty & staff') ?></span><h2><?= h($person['name']) ?></h2>
                <?php if (filter_var($person['email'], FILTER_VALIDATE_EMAIL)): ?><p><a href="mailto:<?= h($person['email']) ?>"><?= h($person['email']) ?></a></p><?php endif; ?>
                <p class="result-location"><span aria-hidden="true">⌖</span><?= h($person['location']) ?></p>
              </article>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>
      </section>
    <?php endif; ?>
  <?php endif; ?>
</main>
<?php require __DIR__ . '/includes/footer.php'; ?>
