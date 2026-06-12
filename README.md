# Attendance Tracker System

## Requirements
- PHP 8.1+, Composer, MySQL 8+, Node.js

## Setup
1. `composer install`
2. `cp .env.example .env` and configure DB credentials
3. `php artisan key:generate`
4. `php artisan migrate --seed`
5. `npm install && npm run build`
6. `php artisan serve`

## Assumptions
- Teachers are not authenticated (auth can be added as an extension)
- "Today midnight" means end of today (23:59:59)
- Attendance can be updated if submitted again for the same date