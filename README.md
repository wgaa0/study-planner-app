# Study Planner App
A web app that lets students create courses/projects, tasks, deadlines, upload resources and view analytics. Built with **PHP** for backend rendering, **TailwindCSS** for styling, and **Chart.js / FullCalendar** for analytics.

---

## Requirements
- PHP ≥ 8.2
- Node.js ≥ 18
- npm ≥ 9
- XAMPP (Apache + MySQL) ≥ 8.2
- TailwindCSS (bundled via npm)
- FullCalendar and Chart.js assets are included in public/assets/vendor for easier setup. No extra install needed.

---

## Installation & Setup (Windows)
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
    - Make sure both **Apache** and **MySQL** are running.

6. Import the database:
    - open http://localhost/phpmyadmin
    - create a database (name: `study_manager`)
    - select the `study_manager` database
    - Go to the **Import** tab.
    - Choose the file located at `db\study_manager.sql`
    - Click **Go** to execute the script and create the tables.

7. Open the app in your browser: http://localhost/study-planner-app/public/login.php

---

## Usage
- Register for a new account, or log in with a demo user
- Create courses and attach resources
- Add tasks with deadlines and associate them with courses.
- Manage events with the interactive calendar.
- View analytics to track study progress (completed tasks per week, deadlines, etc.)

## Contributing
Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.