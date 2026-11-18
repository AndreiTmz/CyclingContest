# Cycling Contest

A PHP web application for running a cycling competition. Visitors can register for events and browse competitors, standings, photos, videos, and route maps. Administrators can update competitor results and status.

## Features

- Competitor registration with category selection and input validation
- Competitor directory with name, gender, and age-category filters
- Category standings with finish positions and competitor status
- Admin tools for managing results and competitor status
- Photo gallery, event videos, and interactive route maps

## Tech Stack

- PHP 8.3 with Apache
- MySQL 8 with `mysqli`
- Docker Compose
- Google Maps JavaScript API and Routes API for route maps (optional)
- phpMyAdmin for local database administration

## Run with Docker

### Requirements

- Docker Desktop or Docker Engine with the Compose plugin
- A web browser

### Start

In the repository root, copy the example environment file ".env.example".
Rename the copy to ".env"

To enable route maps, add a Google Maps API key to `.env`:

```dotenv
GOOGLE_MAPS_API_KEY=your_google_maps_api_key
```

Enable the Maps JavaScript API and Routes API for the Google Cloud project associated with the key. Without a key, the rest of the application works, but route-map buttons are disabled.

Build and start the services:

```sh
docker compose up --build -d
```

Open the application at [http://localhost:8000](http://localhost:8000). phpMyAdmin is available at [http://localhost:8080](http://localhost:8080).

The local database defaults are `cycling_contest` with username `app` and password `app`. These development defaults should not be used in a public deployment.

MySQL imports `database/db_init.sql` the first time it creates its data directory. This creates the tables and seeds example competition data. Existing database volumes are not reinitialized automatically.

### Set the Admin Password

The seed data creates an admin account with username `admin`; the database contains only its password hash. To set a local password, generate a hash inside the PHP container:

```sh
docker compose exec app php -r "echo password_hash('choose-a-local-password', PASSWORD_DEFAULT), PHP_EOL;"
```

In phpMyAdmin, select the `cycling_contest` database, open the SQL tab, and run this query with the generated hash in place of the placeholder:

```sql
UPDATE admin_accounts
SET password_hash = '<generated-hash>'
WHERE username = 'admin';
```

Then sign in at [http://localhost:8000/login.php](http://localhost:8000/login.php).

### Useful Commands

```sh
# View application and database logs
docker compose logs -f app db

# Stop the containers and keep the database volume
docker compose down

# Start the stopped containers again
docker compose start
```

To remove the database and all local data, run `docker compose down -v`. **This permanently deletes the database volume.** The seed SQL runs again only after MySQL creates a fresh data directory.

## Project Structure

```text
admin.php                  Admin results and status management
competitors.php            Competitor directory and filters
database/db_init.sql       Schema and seed data
docker/                    PHP/Apache image configuration
docker-compose.yml         App, MySQL, and phpMyAdmin services
images/                    Page and gallery images
js/                        Gallery and route-map behavior
register.php               Competitor registration
results.php                Category standings
shared/                    Database connection and page templates
videos/                    Competition videos
```

## Notes

This project is intended as a learning and portfolio application. Database credentials and initial admin password are convenient for a local demo and should not be reused in a public or production deployment.