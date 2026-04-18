# Blogify - Custom PHP Blog Platform

Blogify is a custom PHP blog platform with support for subdomains, markdown editing, and a powerful dashboard.

## Minimum System Prerequisites (Install these on your NEW OS)

To run this project with a single command, you only need to install these three things on your new system:

1. **Docker & Docker Compose**: This handles the server, database, and background processes automatically.
2. **mkcert**: This is needed for local HTTPS support.
3. **git**: To clone the repository from GitHub.

---

## Quick Start (The One-Command Setup)

If you have the prerequisites above, you can set up everything with one command:

```bash
./setup.sh
```

---

## Getting Started (Docker)

This is the easiest way to run the project.

1. **Clone the repository**
2. **Generate SSL Certificates**:
   The Nginx configuration requires SSL. Use `mkcert` to generate them:
   ```bash
   mkdir -p app/SSL
   mkcert -cert-file app/SSL/_wildcard.blogify.dev+1.pem -key-file app/SSL/_wildcard.blogify.dev+1-key.pem "*.blogify.dev" blogify.dev
   ```
3. **Start the containers**:
   ```bash
   docker-compose up -d
   ```
4. **Run migrations and seed data**:
   ```bash
   docker exec -it blogify_php php migrate.php
   docker exec -it blogify_php php seed_categories.php
   ```
5. **Access the application**:
   - URL: `https://blogify.dev` (requires local DNS setup) or `http://localhost`
   - Vite (Assets): Running automatically via the `node` container.

---

## Getting Started (Manual Local Setup)

1. **Install Dependencies**:
   ```bash
   composer install
   npm install
   ```
2. **Environment Configuration**:
   ```bash
   cp .env.example .env
   ```
   *Edit `.env` with your local database credentials.*
3. **Setup Database**:
   Create a database named `blogify` and run:
   ```bash
   php migrate.php
   php seed_categories.php
   ```
4. **Run the Application**:
   - **PHP Server**: `composer run serve`
   - **Vite (Frontend)**: `npm run dev`

---

## Project Structure

- `app/`: Core application logic (MVC).
- `public/`: Publicly accessible files (entry point, assets).
- `resources/`: Frontend templates and assets.
- `migrations/`: Database schema definitions.
- `docker/`: Docker configuration files.

---

## Development Notes

- **Subdomains**: The project supports wildcard subdomains (e.g., `user.blogify.dev`). Ensure your local DNS (like `dnsmasq` or `/etc/hosts`) points `*.blogify.dev` to `127.0.0.1`.
- **SSL**: Local development uses `mkcert` for trusted HTTPS certificates.
