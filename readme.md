# PulseFit Admin Login Process

This project includes a simple PHP session-based admin login for the protected area in `admin/`.

## Overview

- Public users can browse the main site pages without authentication.
- Admin access starts from `login`.
- Successful login creates a PHP session.
- Protected admin page verify the session before showing content from id and password hash stored in file.
- Sessions expire after 5 minutes of inactivity.

## Files Used In The Login Flow

- `setup.php` generates the admin credential file.
- `data/admin.php` stores the admin username, salt, and password hash.
- `login.php` validates submitted credentials and starts the session.
- `admin/index.php` protects the admin page and enforces the inactivity timeout.
- `logout.php` destroys the session and sends the user back to the login page.
- `partials/nav.php` switches the navigation link between Admin and customers.

## First-Time Setup

Before the login page can work, run `setup.php` once in the browser.

Example:

```text
http://localhost/pulsefit_site/setup.php
```

What `setup.php` does:

1. Creates a SHA-256 hash using the configured salt and password.
2. Writes the generated values into `data/admin.php`.
3. Displays a completion message with a link to `login.php`.

Important:

- Deleted `setup.php` immediately after running it.
- The script contains the plain-text password and should not remain on the server.
- These values are checked in `login.php` against the constants stored in `data/admin.php`.

## How Login Works

1. Go to Login.
2. Enter the admin username and password.
3. On form submission, `login.php` reads the stored constants from `data/admin.php`.
4. The submitted password is hashed as:

```text
SHA-256(ADMIN_SALT + password)
```

5. The computed hash is compared with current password hash.
6. If both the username and hash match:
	 - the user is redirected to list of customers
7. If the credentials do not match, the page shows:
```text
Invalid credentials. Please try again.
```

## Session Protection
- `SESSION_TIMEOUT` is set to `300` seconds, which is 5 minutes.
- If the user is inactive for more than 5 minutes:
	- the session is cleared
	- the user is redirected to `../login.php?reason=timeout`

The admin page also shows a visible countdown timer for the remaining session time.

## Notes

- Passwords are not stored in plain text inside `data/admin.php`.
- The current implementation uses salted SHA-256 hashing.
- Since this is a simple PHP project, there is only one admin account defined by constants.
