# Map My Uni — Campus Locator

A PHP and MySQL campus directory for the Delhi NCR campus. It keeps the live site’s university-blue and gold palette, campus video, photo gallery, and separate Faculty and Campus search flows. The pages use PDO prepared statements and are designed to run locally with XAMPP.

## Run with XAMPP

1. Copy this project folder into `C:\xampp\htdocs\mapmyuni`.
2. Open XAMPP Control Panel and start **Apache** and **MySQL**.
3. Visit `http://localhost/phpmyadmin`, choose **Import**, select this project’s `schema.sql`, and import it. This creates the `mapmyuni` database and seeds the campus facility records.
4. Open PowerShell in `C:\xampp\htdocs\mapmyuni` and import the supplied faculty CSV files:

   ```powershell
   & C:\xampp\php\php.exe scripts\import-faculty.php
   ```

5. Visit `http://localhost/mapmyuni/`.

The included XAMPP defaults are `127.0.0.1`, database `mapmyuni`, user `root`, and a blank password. If your MySQL root account has a password, set `MAPMYUNI_DB_PASS` in Apache’s environment or update the local defaults in `includes/database.php`. Keep real hosting credentials out of public files and never use the XAMPP root account on a public server.

## What connects to MySQL

- `faculty.php` searches and filters faculty and staff from the `faculty` table.
- `campus.php` searches campus places and faculty room listings. The schema seeds 22 places visible in the live campus finder; room or floor details are left blank where the live listing did not provide them.
- `contact.php` validates the form and stores messages in `contact_messages`. Review submissions in phpMyAdmin. The form does not send email; email delivery needs an SMTP service and configuration.
- `index.php` reads the directory totals and a small faculty preview.

The faculty import reads all `.csv` files under `data/` and is safe to run again after updating those files. The gallery and campus video are local files under `assets/`, so the site does not depend on the live site to load its imagery.

## Project files

- `schema.sql` — database, tables and initial campus places.
- `includes/database.php` — XAMPP/MySQL connection settings.
- `scripts/import-faculty.php` — one-time or repeatable CSV importer.
- `assets/gallery/` and `assets/media/` — campus photos and video.
