# Gym Management System

A Laravel-based gym management platform with integrated biometric access control, built to automate member check-in/check-out using a ZKTeco fingerprint/RFID device instead of manual front-desk tracking.

## Features

- Member management: registration, profile, and membership status tracking.
- Biometric access control: integrates a ZKTeco K40 Pro device over TCP/IP (port 4370) via the `zkteco-sdk-php` library, so fingerprint/RFID scans automatically log attendance and access events.
- Python bridge (`kalper_py`, `Python37`) for lower-level communication with the biometric device where the PHP SDK needed supplementing.
- Scheduled synchronization: `schedule.bat` runs periodic background jobs to pull attendance logs from the device and reconcile them with membership/billing records.
- Automated tests (`tests/`) covering core application logic.
- Asset pipeline managed with Gulp (`gulpfile.js`) for compiling front-end resources.

## Project Structure

```
GYM-Management-System/
├── app/                   # Laravel application logic (models, controllers, services)
├── bootstrap/               # Framework bootstrap files
├── config/                    # Application configuration
├── database/                    # Migrations and seeders
├── public/                        # Web-accessible entry point and compiled assets
├── resources/                       # Views, raw JS/CSS
├── storage/                           # Logs, cache, framework storage
├── tests/                               # Automated test suite
├── vendor/                                # Composer dependencies
├── zkteco-sdk-php-master/                   # ZKTeco device SDK for fingerprint/RFID integration
├── kalper_py/ Python37/                       # Python-side device communication scripts
├── schedule.bat                                 # Scheduled task for attendance sync
├── server.php                                    # Local dev server entry point
├── composer.json / composer.lock                   # PHP dependency management
├── package.json / gulpfile.js                         # Front-end build tooling
└── artisan                                              # Laravel CLI
```

## Tech Stack

Laravel (PHP), MySQL, Python (device communication bridge), zkteco-sdk-php, Gulp

## Setup

1. Install PHP dependencies: `composer install`
2. Install front-end dependencies: `npm install`
3. Copy `.env.example` to `.env` and configure database credentials.
4. Run migrations: `php artisan migrate`
5. Configure the ZKTeco device IP/port and pair it with the `zkteco-sdk-php` integration.
6. Serve the app: `php artisan serve`
7. (Optional) Set up `schedule.bat` as a scheduled task to keep attendance logs synced automatically.
