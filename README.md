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

3. Enable SQLite in PHP:
    - Find you `php.ini` file:
        ```cmd
        php --ini
        ```
    - Open it and uncomment these lines (remove the semicolon):
        ```
        extension=sqlite3
        extension=pdo_sqlite
        ```
    - Restart your web server if needed

4. Configure the app for SQLite:
    - Edit `config/config.php` and change line 3 to: `$driver = 'sqlite';`

5. Set up the database:
    ```cmd
    sqlite3 db/study_manager.sqlite ".read db/study_manager.sqlite.sql
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
- [ ] Add SQLite support for easy setup
- [ ] Style events and analytics pages
- [ ] Input validation for forms
- [ ] Collaboration features (projects)