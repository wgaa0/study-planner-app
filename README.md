# Study Planner App
A web app that lets students create courses/projects, tasks, deadlines, upload resources and view analytics. Built with **PHP** for backend rendering, **TailwindCSS** for styling, and **Chart.js / FullCalendar** for analytics.

---

## Requirements
- PHP ≥ 8.2 (with **MySQL** or **SQLite** support)
- Node.js ≥ 18
- npm ≥ 9
- **Database**: MySQL (via XAMPP) **OR** SQLite (simpler setup)
- TailwindCSS (bundled via npm)
- FullCalendar and Chart.js assets are included in public/assets/vendor for easier setup. No extra install needed.

---

## Prerequisites & Common Pitfalls
Before you begin, make sure your PHP environment is configured correctly.

1. is PHP in your System's PATH?
This guide assumes the php command is globally available. Open a command prompt and verify this:
    ```cmd
    php -v
    ```
If you see a PHP version number, you are all set. If you get an error like `'php' is not recognized...`, you must add the location of your PHP installation (e.g., `c:\php`) to the Windows PATH environment variable.

2. Do You Have a `php.ini` File?
By default, a manual PHP install does not create a `php.ini` file. PHP looks for a file named exactly php.ini in its root folder for configuration settings.
- Run `php --ini`. If it says `Loaded Configuration File: (none)`, you need to create one.
- In your PHP folder (e.g., `C:\php`), find the file named `php.ini-development`.
- Copy and rename this file to `php.ini`. This activates a development-friendly configuration.

3. Are PHP Extensions Enabled?
For this app's MySQL and SQLite setup to work, specific extensions must be enabled inside your php.ini file.
- Open your `php.ini`
- Uncomment the following lines (remove the semicolon):
    For MySQL (XAMPP Setup):
    ```ini
     ; Make sure the extension directory is enabled
     extension_dir = "ext"

     ; Enable the MySQL extensions
     extension=pdo_mysql
     extension=mysqli
     ```

     For SQLite (SQLite Setup):
     ```ini
     ; Make sure the extension directory is enabled
     extension_dir = "ext"

     ; Enable the SQLite extensions
     extension=pdo_sqlite
     extension=sqlite3
     ```

---

## Installation & Setup (Windows)
## Option 1: MySQL (XAMPP) - Original Setup
1. Clone the repository into XAMPP's `htdocs` folder (typically `C:\xampp\htdocs` on Windows):
    ```cmd
    cd C:\xampp\htdocs
    git clone https://github.com/wgaa0/study-planner-app
    ```

2. Move into the project folder:
    ```cmd
    cd study-planner-app
    ```

3. Install dependencies:
    ```cmd
    npm install
    ```

4. Compile TailwindCSS (for development):
    ```cmd
    npm run dev:css
    ```

   For production:
   ```cmd
   npm run build:css
   ```

5. Start XAMPP (Apache + MySQL):
    - Launch **XAMPP Control Panel** and make sure both **Apache** and **MySQL** are running.

6. Import the database:
    - open http://localhost/phpmyadmin
    - create a database (name: `study_manager`)
    - select the `study_manager` database
    - Go to the **Import** tab.
    - Choose the file located at `db\study_manager.sql`
    - Click **Go** to execute the script and create the tables.

7. Open the app in your browser: http://localhost/study-planner-app/public/login.php

---
## Option 2: SQLite (Simplified Setup)
1. Clone the repository:
    ```cmd
    git clone https://github.com/wgaa0/study-planner-app
    cd study-planner-app
    ```

2. Install dependencies:
    ```cmd
    npm install
    npm run build:css
    ```

3. Configure PHP for SQLite:
   - **First, locate your `php.ini` file.** Run `php --ini`. If it says `(none)`, follow the instructions in the "Common Pitfalls" section above to create it from `php.ini-development`.
   - **Open the `php.ini` file** and ensure the following three lines are present and have the leading semicolon (`;`) removed:
     ```ini
     ; Make sure the extension directory is enabled
     extension_dir = "ext"

     ; Enable the SQLite extensions
     extension=pdo_sqlite
     extension=sqlite3
     ```

4. Configure the app for SQLite:
    - Edit `config\config.php` and change line 3 to: `$driver = 'sqlite';`

5. Set up the database:
    ```cmd
    sqlite3 db\study_manager.sqlite ".read db/study_manager.sqlite.sql"
    ```

6. Start the development server:
    ```cmd
    php -S localhost:8000 -t public
    ```

7. Open the app in your browser: http://localhost:8000/login.php

---

## Usage
- Register for a new account, or log in with a demo user
- Create courses and attach resources
- Add tasks with deadlines and associate them with courses.
- Manage events with the interactive calendar.
- View analytics to track study progress (completed tasks per week, deadlines, etc.)

---

## Contributing
Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

---

## Roadmap
- [x] Add SQLite support for easy setup
- [ ] Style events and analytics pages
- [ ] Input validation for forms
- [ ] Collaboration features (projects)