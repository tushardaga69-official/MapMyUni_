<?php
declare(strict_types=1);

if (PHP_SAPI !== 'cli') {
    http_response_code(404);
    exit;
}

require_once __DIR__ . '/../includes/bootstrap.php';

$dataDirectory = realpath(__DIR__ . '/../data');
if ($dataDirectory === false) {
    fwrite(STDERR, "The data folder could not be found.\n");
    exit(1);
}

$files = glob($dataDirectory . DIRECTORY_SEPARATOR . '*.csv') ?: [];
sort($files, SORT_NATURAL | SORT_FLAG_CASE);
if ($files === []) {
    fwrite(STDERR, "No CSV files were found in the data folder.\n");
    exit(1);
}

$imported = 0;
$pdo = null;
try {
    $pdo = db();
    $upsert = $pdo->prepare(
        'INSERT INTO faculty (name, email, department, location, record_key)
         VALUES (:name, :email, :department, :location, :record_key)
         ON DUPLICATE KEY UPDATE
           name = VALUES(name), email = VALUES(email), department = VALUES(department), location = VALUES(location)'
    );
    $pdo->beginTransaction();
    foreach ($files as $file) {
        $handle = fopen($file, 'rb');
        if ($handle === false) {
            throw new RuntimeException('Could not open CSV file: ' . basename($file));
        }

        $headers = fgetcsv($handle, null, ',', '"', '\\');
        if (!is_array($headers)) {
            fclose($handle);
            continue;
        }
        $headerIndex = [];
        foreach ($headers as $index => $header) {
            $header = preg_replace('/^\xEF\xBB\xBF/', '', (string)$header);
            $key = strtolower((string)preg_replace('/[^a-z0-9]/i', '', trim($header)));
            $headerIndex[$key] = $index;
        }

        if (!isset($headerIndex['name'], $headerIndex['department'])) {
            fclose($handle);
            throw new RuntimeException('A CSV is missing its Name or Department column: ' . basename($file));
        }

        while (($row = fgetcsv($handle, null, ',', '"', '\\')) !== false) {
            $value = static function (string $field) use ($headerIndex, $row): string {
                if (!isset($headerIndex[$field])) {
                    return '';
                }
                return trim((string)($row[$headerIndex[$field]] ?? ''));
            };

            $name = $value('name');
            if ($name === '') {
                continue;
            }
            $email = $value('email');
            $department = $value('department');
            $location = $value('location');

            // A source CSV contains a repeated header attached to a malformed row.
            // Skip header labels that were shifted into data columns instead of
            // creating a fake person or department in the searchable directory.
            $normalizeHeaderValue = static function (string $value): string {
                return strtolower((string)preg_replace('/[^a-z0-9]/i', '', trim($value)));
            };
            if (
                $normalizeHeaderValue($name) === 'name'
                || in_array($normalizeHeaderValue($email), ['name', 'email', 'department', 'location'], true)
                || in_array($normalizeHeaderValue($department), ['name', 'email', 'department', 'location'], true)
                || in_array($normalizeHeaderValue($location), ['name', 'email', 'department', 'location'], true)
            ) {
                continue;
            }

            $location = preg_replace('/\s+/', ' ', $location) ?? '';
            $location = preg_replace('/\b(FLOOR)\s+FLOOR\b/i', '$1', $location) ?? $location;
            $location = preg_replace('/,\s*/', ', ', $location) ?? $location;
            $recordKey = hash('sha256', strtolower($name . "\0" . $email . "\0" . $department));

            $upsert->execute([
                ':name' => mb_substr($name, 0, 150),
                ':email' => mb_substr($email, 0, 190),
                ':department' => mb_substr($department, 0, 190),
                ':location' => mb_substr($location, 0, 255),
                ':record_key' => $recordKey,
            ]);
            $imported++;
        }
        fclose($handle);
        fwrite(STDOUT, 'Imported ' . basename($file) . "\n");
    }
    $pdo->commit();
} catch (Throwable $error) {
    if ($pdo instanceof PDO && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    fwrite(STDERR, 'Import stopped. Check that XAMPP MySQL is running, schema.sql is imported and includes/database.php has the correct credentials. Details: ' . $error->getMessage() . "\n");
    exit(1);
}

fwrite(STDOUT, "Done. Processed $imported faculty and staff entries.\n");
