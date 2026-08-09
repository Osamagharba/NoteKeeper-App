# 📝 NoteKeeper – Modern & Secure PHP Note-Taking Web Application

[![PHP Version](https://img.shields.io/badge/PHP-8.0%2B-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://www.php.net/)
[![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://www.mysql.com/)
[![JavaScript](https://img.shields.io/badge/JavaScript-ES6%2B-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black)](https://developer.mozilla.org/en-US/docs/Web/JavaScript)
[![CSS3](https://img.shields.io/badge/CSS3-Modular-1572B6?style=for-the-badge&logo=css3&logoColor=white)](https://developer.mozilla.org/en-US/docs/Web/CSS)
[![License](https://img.shields.io/badge/License-MIT-green.style=for-the-badge)](LICENSE)

**NoteKeeper** is a sleek, modern, and responsive web application built with PHP, MySQL, Vanilla JavaScript, and a 4-tier CSS architecture. It provides a secure user authentication system and an intuitive dashboard for managing user notes with real-time feedback and dynamic styling.

---

## Technical Overview

The application is structured around a procedural PHP architecture that enforces secure data persistence, modular UI rendering, and environment isolation.

### Key Capabilities

- **User Authentication**: Full registration, login, and session-managed logout workflow powered by PHP's native `password_hash()` and `password_verify()` functions.
- **SQL Injection Prevention**: All persistent queries utilize MySQLi prepared statements (`mysqli_prepare` and `mysqli_stmt_bind_param`) with explicit parameter type binding.
- **Environment Configuration**: Dynamic environment parser in `includs/config.php` that loads configuration keys into `$_ENV` and `putenv()`, keeping credentials separated from application logic.
- **4-Tier CSS Architecture**: Modular styling layout structured into design tokens (`variables.css`), global base resets (`global.css`), shared UI elements (`components.css`), and page-specific rules.
- **Responsive Interface**: Mobile-first fluid grid and flexbox layout designed to maintain usability across desktop, tablet, and mobile viewports.
- **Client & Server Validation**: Client-side validation controllers (`camelCase` naming conventions) paired with server-side validation and sanitized responses.

---

## Application Architecture & Directory Structure

The project follows a clean separation of concerns across configurations, core logic, controllers, and stylesheets:

```text
NoteKeeper/
├── .env.example          # Template schema for environment variables
├── .gitignore            # Git exclusion rules preventing credential tracking
├── schema.sql            # Database creation schema for users and notes tables
├── index.php             # Login page controller and view
├── register.php          # Registration page controller and view
├── Note-Taking-page.php  # Authenticated user dashboard controller and view
├── logout.php            # Session termination endpoint
│
├── includs/
│   ├── config.php        # Environment loader and database constant definitions
│   └── auth.php          # Core authentication logic, session handling, and CRUD operations
│
├── Script/
│   ├── index.js          # Login form validation controller
│   ├── register.js       # Registration form validation controller
│   └── Note-Taking-page.js # Dashboard interaction and dynamic note styling controller
│
└── Style/
    ├── variables.css     # Design tokens (Color palette, typography, radii)
    ├── global.css        # Base reset rules, body defaults, and custom scrollbar
    ├── components.css    # Shared component classes (Inputs, buttons, flash alerts)
    ├── index.css         # Login page layout and presentation
    ├── register.css      # Registration page layout and presentation
    └── Note-Taking-page.css # Dashboard layout, navigation bar, and note cards
```

---

## Configuration & Environment Isolation

Application configuration relies on environment variables loaded at runtime from a local `.env` configuration file:

- **Security Isolation**: Environment files containing database credentials are excluded from version control via `.gitignore`.
- **Template Schema**: The `.env.example` file outlines the expected configuration keys (`DB_HOST`, `DB_USER`, `DB_PASS`, `DB_NAME`) without exposing sensitive infrastructure details.
- **Runtime Binding**: The `loadEnv()` helper function inside `includs/config.php` parses environment key-value pairs at application bootstrap.

---

## Database Architecture

The relational database model consists of two primary tables linked via user ownership:

- **`users` Table**: Stores primary user account records, including unique usernames, BCrypt hashed passwords, and creation timestamps.
- **`notes` Table**: Stores note title, note content, creation timestamps, and a `user_id` column establishing relational association with the user account.
- **Data Schema File**: Database table structures are defined in `schema.sql` for initial database deployment.

---

## Security Model

- **Prepared Statements**: Parameterized MySQL queries prevent SQL injection vulnerabilities.
- **Password Security**: Passwords are hashed using `PASSWORD_DEFAULT` (BCrypt) prior to storage.
- **Session Security**: Session identifiers are regenerated upon authentication (`session_regenerate_id(true)`) to mitigate session fixation risks.
- **Output Sanitization**: User-generated content rendered in views is sanitized using `htmlspecialchars()` with `ENT_QUOTES` and `UTF-8` encoding to prevent Cross-Site Scripting (XSS).

---

## Tech Stack Summary

- **Back-End**: PHP 8.x
- **Database**: MySQL / MariaDB (MySQLi driver with Prepared Statements)
- **Front-End**: Vanilla JavaScript (ES6+)
- **Styling**: Modular CSS3 (Design tokens, Flexbox, CSS Grid)
- **Assets**: FontAwesome 6.5.1, Google Fonts (Inter)

---

## License

This project is open-source software licensed under the [MIT License](LICENSE).
