# NoteKeeper – Modern & Secure PHP Note-Taking Web Application

[![PHP Version](https://img.shields.io/badge/PHP-8.0%2B-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://www.php.net/)
[![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://www.mysql.com/)
[![JavaScript](https://img.shields.io/badge/JavaScript-ES6%2B-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black)](https://developer.mozilla.org/en-US/docs/Web/JavaScript)
[![CSS3](https://img.shields.io/badge/CSS3-Modular-1572B6?style=for-the-badge&logo=css3&logoColor=white)](https://developer.mozilla.org/en-US/docs/Web/CSS)
[![License](https://img.shields.io/badge/License-MIT-green.style=for-the-badge)](LICENSE)

**NoteKeeper** is a sleek, modern, and responsive web application built with PHP, MySQL, Vanilla JavaScript, and a 4-tier CSS architecture. It provides a secure user authentication system and an intuitive dashboard for managing user notes with real-time feedback and dynamic styling.

---

## Features

- **Secure User Authentication**: Full Registration, Login, and Logout flow powered by `password_hash()` and `password_verify()`.
- **SQL Injection Protection**: 100% prepared statements (`mysqli_prepare` & `mysqli_stmt_bind_param`) for all database operations.
- **Environment Configuration**: Dynamic `.env` configuration parser ensuring sensitive database credentials are kept out of source control.
- **4-Tier Modular CSS Architecture**: Clean separation into `variables.css`, `global.css`, `components.css`, and page-specific stylesheets.
- **Mobile-First Responsive Layout**: Optimized UI layout that seamlessly adapts across mobile, tablet, and desktop screens.
- **Front-End & Back-End Validation**: Strict client-side validation (`camelCase` JS controllers) paired with robust back-end parameter verification.
- **Dynamic Note Categorization**: Dynamic color coding for note cards with interactive deletion and character-length enforcement.

---

## Architecture & Project Structure

The project follows a clean directory structure separating CSS design tokens, back-end logic, front-end controllers, and configuration files:

```text
NoteKeeper/
├── .env                  # Environment configuration (DB credentials)
├── .gitignore            # Git exclusion rules
├── index.php             # Login page controller & view
├── register.php          # User registration controller & view
├── Note-Taking-page.php  # Authenticated user dashboard controller & view
├── logout.php            # Session destruction endpoint
│
├── includs/
│   ├── config.php        # Environment loader & DB constants configuration
│   └── auth.php          # Authentication, session manager & note CRUD logic
│
├── Script/
│   ├── index.js          # Login form validation controller
│   ├── register.js       # Registration form validation controller
│   └── Note-Taking-page.js # Dashboard interaction & dynamic styling controller
│
└── Style/
    ├── variables.css     # CSS custom properties (Color palette & tokens)
    ├── global.css        # Base reset, typography, and scrollbar styling
    ├── components.css    # Shared UI components (Inputs, Buttons, Cards, Flash msgs)
    ├── index.css         # Page-specific styles for Login
    ├── register.css      # Page-specific styles for Register
    └── Note-Taking-page.css # Page-specific styles for Notes Dashboard
```

---

## Tech Stack

- **Back-End**: PHP 8.x (Procedural Architecture)
- **Database**: MySQL / MariaDB (MySQLi driver with Prepared Statements)
- **Front-End**: Vanilla JavaScript (ES6+), HTML5
- **Styling**: Vanilla CSS3 (Modular 4-tier Architecture, Flexbox, Grid, Media Queries)
- **Icons & Fonts**: FontAwesome 6.5.1, Google Fonts (Inter)

---

## Getting Started & Installation

### Prerequisites

- [XAMPP](https://www.apachefriends.org/) / WAMP / LAMP stack running **PHP 8.0+** and **MySQL**.
- Web Browser (Chrome, Firefox, Edge, Safari).

### Setup Instructions

1. **Clone the Repository**
   ```bash
   git clone https://github.com/your-username/NoteKeeper-PHP.git
   cd NoteKeeper-PHP
   ```

2. **Database Setup**
   Create a MySQL database named `note_taking` and execute the following schema:

   ```sql
   CREATE DATABASE IF NOT EXISTS note_taking;
   USE note_taking;

   CREATE TABLE IF NOT EXISTS users (
       id INT AUTO_INCREMENT PRIMARY KEY,
       username VARCHAR(50) NOT NULL UNIQUE,
       password VARCHAR(255) NOT NULL,
       created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
   ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

   CREATE TABLE IF NOT EXISTS notes (
       id INT AUTO_INCREMENT PRIMARY KEY,
       title VARCHAR(100) NOT NULL,
       body TEXT NOT NULL,
       user_id INT NOT NULL,
       created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
       FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
   ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
   ```

3. **Configure Environment Variables**
   Create a `.env` file in the root directory based on the `.env.example` template:

   ```bash
   cp .env.example .env
   ```

   Update the `.env` file with your local database credentials:

   ```env
   DB_HOST='localhost'
   DB_USER='root'
   DB_PASS=''
   DB_NAME='note_taking'
   ```


4. **Run the Application**
   Place the project folder inside your web server directory (e.g., `xampp/htdocs/NoteKeeper-PHP`) and navigate to:
   ```text
   http://localhost/NoteKeeper-PHP/index.php
   ```

---

## Security Best Practices Implemented

- **Prepared Statements**: Eliminates SQL Injection risks across user inputs.
- **BCrypt Password Hashing**: Passwords stored using `PASSWORD_DEFAULT`.
- **Session ID Regeneration**: Prevents session fixation attacks upon login.
- **Environment Isolation**: Database credentials strictly stored in `.env` and ignored by Git via `.gitignore`.
- **XSS Prevention**: HTML output escaped using `htmlspecialchars()`.

---

## License

This project is licensed under the MIT License – see the [LICENSE](LICENSE) file for details.
