# Personal Wellness Tracker

A PHP and MySQL wellness journal for hydration, exercise, sleep and mood records.

## Features

- Separate activity log pages
- Dashboard and reports
- Reminder management
- User profiles and authentication

## Technology

PHP, MySQL, HTML, CSS and JavaScript.

## Local setup

Clone the repository:

```bash
git clone https://github.com/nasirabbas827/wellness-final.git
cd wellness-final
```

1. Install a local PHP web server and MySQL, such as XAMPP.
2. Place the project in the server's document root, or configure a virtual host pointing to this folder.
3. Import `Database/wellnessdb.sql` using phpMyAdmin or your MySQL client.
4. Update the database connection in `config.php` to match your local database name and credentials.
5. Start Apache and MySQL, then open `http://localhost/wellness-final/`.

## Repository layout

| Path | Purpose |
|---|---|
| `CSS/` | Project resources |
| `Database/` | SQL database export |
| `config.php` | Database connection configuration |
| `images/` | Project resources |
| `index.php` | Web entry page |

## Development notes

Setup steps describe local development. Configure your own database, mail and API credentials where applicable. Review debug settings and replace development secrets before deploying. The application was not executed as part of this documentation update.

Maintained by [Nasir Abbas](https://github.com/nasirabbas827).
