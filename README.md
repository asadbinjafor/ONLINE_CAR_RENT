# Online Car Rent

## Project Scenario Summary

**Online Car Rent** is a web-based application that helps customers rent cars for different purposes—daily commutes, family trips, group travel, or business use. The site lists vehicles by category (Private car, Microbus, Pick-up, SUV, Sedan, Luxury), shows pricing per day, availability, and descriptions, so visitors can browse listings and members can book rentals online with flexible payment options.

The system works like a small car rental platform with two registered roles plus a public (guest) browsing experience:

**Admin** — Main controller of the platform: manages the full car inventory (create, edit, delete listings with images), removes member accounts, views complete rent order history with filters, and can delete any blog post for moderation.

**Member** — Registered user who can browse cars by category, view car details, place rental orders with start/end dates, review an invoice (cancel or finalize), choose a payment method (Credit Card, bKash, Nagad, Bank Transfer, Cash on Delivery), and see rental history in their profile. Members can also post rental experiences on the blog and delete their own posts.

**Guest (non-registered)** — Can view the home page, browse cars by category, read car details, and read blog posts. Cannot rent cars, post blogs, or access profile until registered and logged in as a member.

**Typical workflow:** Admin adds cars to the fleet → members browse and select a vehicle → member places an order and sees an invoice → member finalizes and pays → order is confirmed and stored in rental history → members share experiences on the blog page.

This project was built as **Web Technologies — Project 05**, following a shared database schema and PHP MVC structure with security, validation, and AJAX features required by the assignment. The MySQL database name is **`project5`** (same as the project folder name).

---

## Technologies & Topics Used

The project combines front-end, back-end, database, and security practices taught in web technologies courses.

### Front-End

| Topic | How it is used in this project |
|-------|--------------------------------|
| **HTML5** | Semantic page structure, forms, tables, navigation, car cards, invoice/payment pages, admin panels, blog layout |
| **CSS3** | Layout (Flexbox, CSS Grid), custom properties (`:root` variables), responsive design (`@media`), automotive-themed UI (dark navy/orange palette, cards, badges, status pills, hero section, stats grid) |
| **JavaScript** | Client-side form validation (register, profile, car, order, blog, payment), AJAX (`fetch`) for live car search, dynamic cost calculation, order cancel, member delete, and blog post/delete without full page reload |

### Back-End

| Topic | How it is used in this project |
|-------|--------------------------------|
| **PHP** | Server-side logic, session management, routing, controllers, models, views |
| **MVC pattern** | Separation into `controllers/`, `models/`, `views/`, `config/` |
| **PDO (MySQL)** | Database connection with prepared statements (SQL injection prevention) |
| **Sessions & cookies** | Login state, roles (`admin` / `member`), “Remember Me” (30 days), CSRF tokens |
| **File upload** | Profile pictures and car images with server-side MIME (JPEG/PNG) and size checks (max 2 MB) |
| **Password security** | `password_hash()` on register; `password_verify()` on login |

### Database

| Topic | How it is used in this project |
|-------|--------------------------------|
| **MySQL** | Relational database **`project5`** |
| **Tables** | `users`, `cars`, `orders`, `payments`, `blogs` |
| **Keys & integrity** | Foreign keys, `ON DELETE CASCADE` / `RESTRICT`, unique email on users |

### Other Web Topics

| Topic | How it is used in this project |
|-------|--------------------------------|
| **AJAX / JSON** | API-style endpoints return JSON for car search, order cost calculation, order cancel, blog create/delete, admin member delete |
| **XSS prevention** | `htmlspecialchars()` (via `Security::e()`) when displaying user content |
| **CSRF protection** | Hidden token on forms; verified on POST requests |
| **Responsive UI** | Mobile-friendly navigation toggle and responsive grids |
| **Apache (XAMPP)** | Local hosting; `index.php` as front controller with `?route=` URLs (no `.htaccess` required) |

---

## Default User Credentials

After importing `database.sql`, you can log in with these demo accounts:

| Role | Display Name | Email | Password |
|------|--------------|-------|----------|
| **Admin** | Admin User | `admin@carrent.com` | `password123` |
| **Member** | John Member | `member@carrent.com` | `password123` |

**Note:** Both accounts use the same password: **password123**

New **admin** or **member** accounts can also be created from the public registration page (role dropdown: admin / member).

---

## How to Run the Project

1. Install **XAMPP** and start **Apache** and **MySQL**.
2. Copy the project folder to `htdocs` (e.g. `C:\xampp\htdocs\project5`).
3. Import **`database.sql`** in phpMyAdmin (**Import** tab, full file) — creates database **`project5`** and seed data.
4. Open: **http://localhost/project5/index.php**
5. Log in with any default email and password from the table above.

If the project folder name is not `project5`, update **`BASE_URL`** in `config/app.php`.

**URLs:** Pages use `index.php?route=/path` (e.g. `index.php?route=/login`). Navigation links are generated automatically; you do not need `.htaccess`.

---

## Main Modules (Assignment Tasks)

| Task | Module | Main features |
|------|--------|---------------|
| **Task 1** | Auth & profile | Register (admin/member), login, remember me, profile update (address, phone, picture, password), home page with featured cars, category browsing |
| **Task 2** | Admin content | Car CRUD with image upload, delete members (AJAX), view all rent order history with filters, admin dashboard stats |
| **Task 3** | Member rental | Car selection, order placement, invoice (cancel/finalize), payment method selection, rental history in profile, dynamic cost calculation (AJAX) |
| **Task 4** | Blog | Post rental experiences, view all blogs, delete own post (member), delete any post (admin), AJAX create/delete |

---

## Project Folder Overview

```
config/         → App settings, database, routes
controllers/    → Page logic and JSON APIs (ApiController, AdminApiController)
models/         → Database queries (PDO)
views/          → HTML/PHP templates (admin, auth, cars, orders, blog, layouts, partials)
includes/       → Bootstrap, Auth, Security helpers
public/css/     → Stylesheets (base, components, responsive)
public/js/      → Validation and AJAX scripts
public/uploads/ → Profile and car images (ignored by Git via .gitignore)
database.sql    → Database schema and seed data
index.php       → Application entry point
.gitignore      → Excludes uploaded images from Git commits
```

---

## Security Features (Summary)

- Prepared statements for all database queries  
- Hashed passwords (never stored as plain text)  
- CSRF tokens on form submissions  
- Escaped output to reduce XSS risk  
- Role-based access (`admin` / `member`; guests unauthenticated)  
- Validated file uploads (type and size)  
- Secure remember-me token stored as HMAC hash  

---

This README describes the project scenario, technologies used, and default login details for reviewers, instructors, and GitHub visitors.
