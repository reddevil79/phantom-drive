# Bakery Management System

A full-featured PHP & MySQL web application for a bakery/restaurant business, covering the customer ordering journey end-to-end (browse → cart → checkout) as well as an admin panel for managing orders and menu items.

## Features

**Customer-facing**
- User authentication: registration, login, logout, and forgotten-password recovery.
- Menu browsing by dish and category, with product images and descriptions.
- Shopping cart with add/update/remove functionality.
- Checkout flow that converts a cart into a placed order.
- Product ratings, allowing customers to review dishes after ordering.
- Search across the menu.

**Admin-facing**
- Admin panel for managing incoming orders (view, update status, delete).
- Product/dish management tied to the storefront catalog.

## Project Structure

```
Bakery-Management-System/
├── admin/               # Admin panel for order and menu management
├── css/ scss/            # Stylesheets (custom SCSS compiled to CSS)
├── js/                     # Client-side interactivity
├── images/ fonts/            # Static assets
├── connection/ database/       # DB connection handling and schema
├── index.php                    # Storefront homepage
├── login.php / registration.php / Forget.php / logout.php   # Auth flows
├── category.php / dishes.php / search.php                     # Menu browsing
├── cart.php / checkout.php / product-action.php                 # Ordering flow
├── orders.php / delete_orders.php                                  # Order management
├── insert_rating.php                                                 # Product review submission
├── check_user.php                                                     # Session/auth guard
└── config.php                                                          # App configuration
```

## Tech Stack

PHP, MySQL, JavaScript, HTML/CSS, SCSS

## Development Approach

Built using Scrum, with a full sprint-based workflow including Gantt charts, sprint backlogs, and ER/use-case/activity diagrams to plan the system before implementation.

## Setup

1. Import the database schema from `database/` into MySQL.
2. Configure database credentials in `config.php`.
3. Serve the project root with PHP (e.g. `php -S localhost:8000` or via XAMPP/Apache).
4. Visit `index.php` to browse the storefront, or `admin/` to manage orders as an administrator.
