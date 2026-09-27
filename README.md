# Online Car Rent

A PHP car rental application with guest browsing, member bookings, payments, profiles, blog posts, and an admin dashboard. The server uses PostgreSQL through PDO and stores uploaded profile and car images in Supabase Storage.

## Features

- Guests can browse cars and blog posts.
- Members can register, rent cars, review invoices, record payment details, manage profiles, and post on the blog.
- Admins can manage cars and images, members, orders, and blog content.
- Forms use CSRF tokens, role checks, password hashing, and prepared SQL statements.

## Project layout

- `index.php`, `config/`, `controllers/`, `models/`, `views/`, `includes/`: PHP application.
- `public/`: CSS, JavaScript, and local development uploads.
- `supabase/schema.sql`: PostgreSQL schema for a new database.
- `Dockerfile`, `deploy/`, `webroot/`: Render PHP/Apache runtime.
- `vercel-proxy/`: Static startup page and proxy configuration.

Public registration creates member accounts. An administrator is promoted through the database by the project owner.
