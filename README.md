# Edupme Laravel Project Documentation

## Overview
Edupme is a feature-rich e-learning platform built with Laravel 8. It supports individual learners, organizations, and instructors to manage, deliver, and consume online courses. The platform includes user authentication, course management, subscriptions, quizzes, reviews, wishlists, and payment integrations.

---

## Main Features & Modules
- **User Management:** Registration, login, email verification, roles, and permissions (Spatie Laravel Permission).
- **Course Management:** Create, update, categorize, and manage courses, including requirements, pricing, images, and metadata.
- **Subscription & Licensing:** Users can subscribe to courses, with support for subscription durations and licensing.
- **Payment Integration:** Supports Paytm, Razorpay, and PayPal for course purchases, with secure checkout and order management.
- **Wishlist & Purchase History:** Users can add courses to wishlists and view their purchase history.
- **Admin Panel:** Separate authentication and dashboard for administrators, with advanced management features.
- **Organization & Invitations:** Organizations can invite users, manage members, and handle group enrollments.
- **Q&A and Reviews:** Courses support Q&A, reviews, and ratings.
- **Notifications & Emails:** Automated emails for registration, purchase confirmations, and contact forms.
- **DataTables Integration:** Advanced data listing and filtering in the admin using Yajra DataTables.
- **File Storage:** Uses AWS S3 for document and image uploads.
- **SEO & CMS:** SEO meta management and CMS pages for static content.

---

## Architecture & Folder Structure
- `app/Http/Controllers/`: All controllers, grouped by domain (Admin, API, Auth, Front).
- `app/Models/`: Eloquent models for all entities (User, Course, Payment, etc.).
- `app/Helper/`: Helper classes for S3 uploads, global functions, and option data.
- `app/Mail/`: Mailable classes for notifications and transactional emails.
- `app/DataTables/`: DataTable classes for advanced admin listings.
- `config/`: Configuration files for services, auth, payments, etc.
- `database/`: Migrations, seeders, and factories.
- `public/`: Public assets, uploads, and entry point (`index.php`).
- `resources/views/`: Blade templates for frontend and admin.
- `routes/`: Route definitions for web, API, and admin.
- `tests/`: Feature and unit tests.

---

## Authentication & Authorization Flow
- Uses Laravel's built-in authentication for users and a separate guard for admins.
- Email verification is required for user activation.
- Role-based access control is enforced using Spatie's package.
- Middleware is used to protect routes (`auth`, `adminauth`, `role`, `permission`).
- API authentication is handled via Laravel Passport.

---

## Course Management Flow
- Admins and authorized users can create and manage courses.
- Courses are associated with categories, authors, and can have related courses.
- Each course includes metadata for SEO, pricing, requirements, and media.
- Users can enroll in courses, and their progress and subscriptions are tracked.

---

## Payment Flow
- Users can purchase courses via Paytm, Razorpay, or PayPal.
- The checkout process is protected by authentication middleware.
- After payment, users receive confirmation and access to the purchased course.
- Invoices are generated and sent via email.

---

## Key Packages Used
- `laravel/passport`: API authentication.
- `spatie/laravel-permission`: Role and permission management.
- `yajra/laravel-datatables`: DataTables integration.
- `maatwebsite/excel`: Excel import/export.
- `anandsiddharth/laravel-paytm-wallet`, `razorpay/razorpay`, `srmklive/paypal`: Payment gateways.
- `league/flysystem-aws-s3-v3`: AWS S3 storage.
- `laravel/socialite`: Social login.
- `cviebrock/eloquent-sluggable`: Slug generation for SEO.

---

## Environment & Setup
- **Requirements:** PHP v8.2, Composer, MySQL, Node.js v18.20.7 (for assets).
- **Configuration:**
  - Copy `.env.example` to `.env` and set up database, mail, AWS S3, and payment gateway credentials.
  - Run `composer install` to install PHP dependencies.
  - Run `php artisan migrate --seed` to set up the database.
  - Run `npm install && npm run dev` to build frontend assets (if applicable).
- **Assets:** Managed in `public/assets/` and `resources/`.

---

## Setup & Installation

1. **Clone the repository:**
   ```bash
   git clone <your-repo-url>
   cd <project-directory>
   ```
2. **Install PHP dependencies:**
   ```bash
   composer install
   ```
3. **Install Node.js dependencies (for frontend assets):**
   ```bash
   npm install
   npm run dev
   ```
4. **Copy and configure environment file:**
   ```bash
   cp .env.example .env
   # Edit .env to set up database, mail, AWS S3, and payment gateway credentials
   ```
5. **Generate application key:**
   ```bash
   php artisan key:generate
   ```
6. **Run database migrations and seeders:**
   ```bash
   php artisan migrate --seed
   ```
7. **Set correct permissions (Linux):**
   ```bash
   chmod -R 775 storage bootstrap/cache
   ```
8. **Start the development server:**
   ```bash
   php artisan serve
   ```

---

## Git Workflow

- **Clone the repository:**
  ```bash
  git clone <your-repo-url>
  ```
- **Create a new branch for your feature or fix:**
  ```bash
  git checkout -b feature/your-feature-name
  ```
- **Check status and stage changes:**
  ```bash
  git status
  git add <file(s)>
  ```
- **Commit your changes:**
  ```bash
  git commit -m "Describe your changes"
  ```
- **Pull latest changes from main branch:**
  ```bash
  git pull origin main
  ```
- **Push your branch to remote:**
  ```bash
  git push origin feature/your-feature-name
  ```
- **Create a Pull Request (PR) on your Git platform (e.g., GitHub, GitLab) and request review.**

---

## Security & Privacy
- **Proprietary Notice:** This project is private and not open source. All code, data, and business logic are confidential and must not be shared externally.
- **User Data:** All user data is protected and handled according to best security practices.

---

## Contact & Support
For internal use only. For technical support, contact the project maintainers or your internal IT team.
