# Attendance Tracker System

A full-stack web application for tracking student attendance across subjects, built with Laravel, MySQL, and Tailwind CSS.

🔗 **Live Demo:** [https://attendance-tracker-production-b2da.up.railway.app](https://attendance-tracker-production-b2da.up.railway.app)

---

## Demo Credentials

| Role    | Email                     | Password   |
|---------|---------------------------|------------|
| Admin   | admin@example.com         | admin1     |
| Teacher | teacher1@example.com      | teacher1   |
| Teacher | teacher2@example.com      | teacher2   |
| Teacher | teacher3@example.com      | teacher3   |

---

## Features

### Authentication & Roles
- Secure login via Laravel Breeze
- Two roles: **Admin** and **Teacher**
- Role-based access control throughout the system

### Admin
- Manage teachers — add, edit, delete teacher accounts and assign subjects
- Manage subjects — add, edit, delete subjects and assign them to teachers
- Full access to all students, attendance records, and dashboard data

### Teacher
- Mark daily attendance for their assigned subjects
- View attendance dashboard filtered to their own subjects
- Search students and view individual attendance records
- Manage students — add, edit, delete and manage subject enrolments

### Dashboard
- Attendance overview with date range and subject filters
- Per-page control (25 / 50 / 100 / 200 records)
- Attendance percentage per student with colour-coded badges (green ≥75%, amber ≥50%, red <50%)
- Bar chart visualisation of attendance percentages (Chart.js)
- Optimised SQL aggregate query — handles 1M+ records in under 1 second using composite indexes

### Mark Attendance
- Select subject to load enrolled students via AJAX
- Mark all present / mark all absent shortcut buttons
- Updates existing records or creates new ones (upsert)

### Search Student
- Live search suggestions as you type (debounced AJAX, 250ms)
- Per-student attendance view with:
  - Per-subject summary cards with progress bars
  - Day-by-day attendance log with pagination
  - Date range filter

### Manage Students
- Add, edit, delete students
- Manage subject enrolments per student
- Paginated list with search
- Teachers can only manage enrolments for their own subjects

---

## Tech Stack

| Layer       | Technology                          |
|-------------|-------------------------------------|
| Backend     | PHP 8.2, Laravel 12                 |
| Auth        | Laravel Breeze                      |
| Frontend    | Tailwind CSS v4, Alpine.js          |
| Charts      | Chart.js                            |
| Database    | MySQL 8                             |
| Build tool  | Vite with @tailwindcss/vite         |
| Deployment  | Railway (Docker)                    |

---

## Local Setup

### Requirements

- PHP 8.2+
- Composer
- Node.js 20+
- MySQL 8+

### Installation

```bash
# 1. Clone the repository
git clone https://github.com/DinukaEk/attendance-tracker.git
cd attendance-tracker

# 2. Install PHP dependencies
composer install

# 3. Install JS dependencies
npm install

# 4. Copy environment file
cp .env.example .env

# 5. Generate application key
php artisan key:generate
```

### Database Configuration

Create a MySQL database:

```sql
CREATE DATABASE attendance_tracker CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

Update `.env` with your database credentials:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=attendance_tracker
DB_USERNAME=root
DB_PASSWORD=your_password
```

### Run Migrations and Seed

```bash
php artisan migrate --seed
```

This creates:
- 1 admin user
- 2 teacher users
- 5 subjects (assigned to teachers)
- 50 students (each enrolled in 3–5 subjects)
- 14 days of attendance history (~80% attendance rate)

### Build Frontend Assets

```bash
npm run build
```

Or for development with hot reload:

```bash
npm run dev
```

### Start the Server

```bash
php artisan serve
```

Visit [http://127.0.0.1:8000](http://127.0.0.1:8000)

---

## Database Schema

```
users
├── id, name, email, password
├── role (admin | teacher)
└── timestamps

subjects
├── id, name, code (unique)
├── user_id (FK → users, nullable)
└── timestamps

students
├── id, name, registration_number (unique)
└── timestamps

student_subject (pivot)
├── student_id (FK → students)
└── subject_id (FK → subjects)

attendances
├── id, student_id (FK), subject_id (FK)
├── date, present (boolean)
└── timestamps
```

### Performance Indexes

```sql
INDEX (subject_id, date)          -- fast date-range queries per subject
INDEX (student_id, subject_id)    -- fast per-student lookups
UNIQUE (student_id, subject_id, date)  -- prevent duplicate records
```

---

## Project Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Admin/
│   │   │   ├── SubjectController.php
│   │   │   └── TeacherController.php
│   │   ├── AttendanceController.php
│   │   ├── DashboardController.php
│   │   ├── StudentController.php
│   │   └── StudentManagementController.php
│   └── Middleware/
│       ├── EnsureUserIsAdmin.php
│       └── EnsureUserIsTeacherOrAdmin.php
├── Models/
│   ├── Attendance.php
│   ├── Student.php
│   ├── Subject.php
│   └── User.php
└── Providers/
    └── AppServiceProvider.php

resources/views/
├── admin/
│   ├── subjects/     (index, create, edit)
│   └── teachers/     (index, create, edit)
├── attendance/
│   └── index.blade.php
├── auth/
│   └── login.blade.php
├── dashboard/
│   └── index.blade.php
├── layouts/
│   └── custom.blade.php
├── profile/
│   └── edit.blade.php
├── students/
│   ├── manage/       (index, create, edit, _form)
│   ├── search.blade.php
│   └── show.blade.php
└── vendor/
    └── pagination/
        └── tailwind-dark.blade.php
```

---

## Deployment (Railway)

The app is deployed on [Railway](https://railway.app) using Docker with a MySQL managed database.

### Environment Variables Required

```env
APP_ENV=production
APP_KEY=your_app_key
APP_DEBUG=false
APP_URL=https://your-app.up.railway.app
ASSET_URL=https://your-app.up.railway.app

DB_CONNECTION=mysql
DB_HOST=mysql.railway.internal
DB_PORT=3306
DB_DATABASE=railway
DB_USERNAME=root
DB_PASSWORD=your_db_password
```

### Docker

The app uses a custom `Dockerfile` for deployment:

```dockerfile
FROM php:8.2-cli
RUN apt-get update && apt-get install -y \
    git curl zip unzip libpng-dev libonig-dev \
    libxml2-dev libzip-dev default-mysql-client \
    && docker-php-ext-install pdo pdo_mysql mbstring xml ctype fileinfo zip
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
WORKDIR /app
COPY . .
RUN composer install --optimize-autoloader
CMD php artisan config:clear && php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=${PORT:-8080}
```

---

## Assumptions Made

- Each student can be enrolled in a minimum of 3 and maximum of 5 subjects
- There are 5 subjects in total
- Attendance is marked once per student per subject per day (duplicate prevention via unique constraint)
- Default dashboard date range is the last 7 days up to today
- Teachers can only view and manage data related to their assigned subjects
- Admins have unrestricted access to all data
- The "advanced search" requirement is fulfilled via AJAX live suggestions + date range + subject filters

---

## Performance

The dashboard query is optimised to handle 1 million+ attendance records in under 1 second:

- Single SQL aggregate query using `LEFT JOIN` with `COUNT` and `SUM` — no PHP-side loops
- Composite indexes on `(subject_id, date)` and `(student_id, subject_id)`
- Results are paginated (configurable: 25 / 50 / 100 / 200 per page)
- Query result caching with Laravel Cache (60 second TTL)

To generate a large test dataset:

```bash
php artisan attendance:generate 1000000
```

