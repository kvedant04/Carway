# Carway

Carway is a small car-listing website repository that contains static HTML pages for various car models, server-side code, and a couple of PHP scripts for authentication and file uploads. It looks intended as a lightweight demo/site for listing new and used cars and allowing simple uploads.

---

## Features

- Static pages for several car models (new and used variants).
- A Node.js-based server to serve pages and assets.
- PHP scripts for user login and car image upload (included).
- `uploads/` folder for uploaded files (image uploads).
- Example pages:
  - MG_Windsor_EV.html
  - Mahindra_BE.html, Mahindra_XEV.html
  - Tata_Curvv_EV.html
  - used-* pages for used car listings
  - manufacturer-specific pages (audi.html, mercedes.html, scorpio.html, forturner.html, etc.)
- Packaging files: `package.json`, `package-lock.json` (Node project metadata).

---

## Repository structure (key files)

- https://github.com/kvedant04/Carway/blob/main/server.js — Node server
- https://github.com/kvedant04/Carway/blob/main/package.json
- https://github.com/kvedant04/Carway/blob/main/package-lock.json
- https://github.com/kvedant04/Carway/blob/main/car_upload.php — PHP upload handler
- https://github.com/kvedant04/Carway/blob/main/login.php — PHP login handler
- https://github.com/kvedant04/Carway/blob/main/login.html — Login page
- https://github.com/kvedant04/Carway/blob/main/about.html — About page
- Static/model pages: e.g. https://github.com/kvedant04/Carway/blob/main/MG_Windsor_EV.html
- Uploads directory (https://github.com/kvedant04/Carway/tree/main/uploads) — intended destination for uploaded files

---

## Prerequisites

- Node.js and npm (for running the Node server)
- If you plan to use the PHP scripts (`login.php`, `car_upload.php`), a PHP runtime (PHP 7+) and optionally a web server (Apache/Nginx) or PHP built-in server.
- (Optional) A database (MySQL / MariaDB / SQLite) if you want to persist users or listings — none is included by default.

---

## Quick start

1. Clone the repository
   - git clone https://github.com/kvedant04/Carway.git
   - cd Carway

2. Install dependencies (if any)
   - npm install

3. Start the Node server
   - node server.js
   - By default many Node demos use port 3000; check `server.js` for the actual port. Open the configured port in your browser (e.g. `http://localhost:3000`).

4. Serving/using the PHP scripts
   - If you want to use the PHP-based login/upload handlers, run a PHP server in the repository root:
     - php -S localhost:8000
   - Or deploy the PHP files to a LAMP/LEMP server (Apache/Nginx + PHP-FPM).
   - Ensure `uploads/` is writable by the webserver user.

Notes:
- The project appears to be a hybrid of Node and PHP — decide whether you want to serve static pages via the Node server or run PHP for dynamic features. Running both simultaneously requires separate ports or an integrated setup.

---

## Configuration & Security

- Ensure `uploads/` has safe permissions (not world-writable in production).
- Validate and sanitize all uploaded files (currently, review `car_upload.php` before production use).
- If using `login.php`, never store plain-text passwords — switch to hashed passwords and use prepared statements to avoid SQL injection (if a DB is added).
- Add environment/config files as needed and avoid committing secrets.

---

## Suggestions / Next steps

- Add a README (this file) to the repo (if you like it, copy it into the repo).
- Add a license (e.g. MIT) in a `LICENSE` file.
- Convert PHP handlers to Node (or vice versa) for a consistent backend stack, or clearly document which parts are served by which runtime.
- Add database support for persistent listings and user accounts.
- Improve upload security (file type check, size limit, random filenames).
- Add unit tests and CI (GitHub Actions).

---

## Contributing

1. Fork the repository.
2. Create a branch for your feature or bugfix.
3. Make changes and test locally.
4. Open a pull request describing your changes.

Please include clear commit messages and keep changes focused.

---

## Author

Built with ❤️ by [Vedansh Pandey] and [Vedant Kolhe]
