# Metal Gear Dle

Lean PHP MVC MVP for a LoLdle-style Metal Gear guessing game, designed to run on shared hosting with PHP 8.x and MySQL/MariaDB.

## Stack

- PHP 8.x
- PDO
- MySQL / MariaDB
- Apache `.htaccess` rewrites
- Browser `localStorage` for anonymous daily tries

## Project Structure

- `public/` web assets and front controller
- `src/` controllers, core classes, repositories, entities, services
- `views/` PHP templates
- `config/` app and database configuration
- `database/` schema and seed SQL for phpMyAdmin

## Local Setup

1. Create a MySQL database named `metalgeardle`.
2. Import [schema.sql](/Applications/MAMP/htdocs/metalgeardle/database/schema.sql) in phpMyAdmin.
3. Import [seed.sql](/Applications/MAMP/htdocs/metalgeardle/database/seed.sql).
4. For an existing local database created from the older schema, run [migration_cleanup.sql](/Applications/MAMP/htdocs/metalgeardle/database/migration_cleanup.sql) to drop the removed columns and old `guesses` table.
5. Import [migration_add_daily_solves.sql](/Applications/MAMP/htdocs/metalgeardle/database/migration_add_daily_solves.sql) to enable the daily winners counter.
6. Update [database.php](/Applications/MAMP/htdocs/metalgeardle/config/database.php) if your MySQL credentials differ from the MAMP defaults.

## Current MVP Features

- Daily classic challenge lookup from the database
- Automatic daily challenge generation with a 4:00 PM Europe/Paris rollover
- Random daily character selection that avoids the previous 4 days
- Character guesses by main name or alias
- Attribute comparison for gameplay-focused fields
- Guess history stored in browser `localStorage` per challenge date
- Daily winners counter backed by the database
- No account requirement for the MVP

## Next Good Steps

- Add close-match logic for selected fields
- Add admin tooling to schedule future daily challenges
- Add optional streaks once the anonymous local flow feels solid
