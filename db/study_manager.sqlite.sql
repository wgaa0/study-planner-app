PRAGMA foreign_keys = ON;

-- Users table
CREATE TABLE users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    email TEXT NOT NULL UNIQUE,
    password_hash TEXT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Courses table
CREATE TABLE courses (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER NOT NULL,
    title TEXT NOT NULL,
    description TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Tasks table
CREATE TABLE tasks (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    course_id INTEGER NOT NULL,
    title TEXT NOT NULL,
    details TEXT,
    status TEXT CHECK(status IN ('todo','in_progress','done')) DEFAULT 'todo',
    due_date DATETIME,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE
);

-- Trigger for updated_at
CREATE TRIGGER tasks_updated_at
AFTER UPDATE ON tasks
BEGIN
    UPDATE tasks SET updated_at = CURRENT_TIMESTAMP WHERE id = NEW.id;
END;

-- Events table
CREATE TABLE events (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER NOT NULL,
    course_id INTEGER,
    task_id INTEGER,
    title TEXT NOT NULL,
    start DATETIME NOT NULL,
    end DATETIME,
    notes TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE SET NULL,
    FOREIGN KEY (task_id) REFERENCES tasks(id) ON DELETE SET NULL
);

-- Resources table
CREATE TABLE resources (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    course_id INTEGER NOT NULL,
    user_id INTEGER NOT NULL,
    file_name TEXT NOT NULL,
    file_path TEXT NOT NULL,
    file_type TEXT,
    file_size INTEGER,
    uploaded_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- USERS
INSERT INTO users (id, name, email, password_hash, created_at) VALUES
(9, 'Aser Alemu', 'aser@gmail.com', '$2y$10$L66StwaB5DAC4Jmp64I4J.vlfutqouNOOf6dfdftzEfEg6JY25lpC', '2025-09-29 16:25:38'),
(10, 'Yaphet Gebreyesus', 'yaphet@gmail.com', '$2y$10$.gudgT7YmSETVyeHaCflk.B2E37LGsJ1XG3HRQ8NjqU8/WpkpaNcK', '2025-09-29 16:26:21'),
(11, 'Yohannes Abebe', 'yohannes@gmail.com', '$2y$10$Yc39FV7KZT0ezYNxrQ5iau5EWjpEcowhnIlC4I0TRB35ujEzxUKj.', '2025-09-29 16:26:52'),
(12, 'Abebe Kebede', 'abebe@gmail.com', '$2y$10$EGFSVLYRdKibYoX7KTm0NOYxDySCPAZ0.C5ySdYnPwh/4yeNItB6y', '2025-09-29 16:39:20');

-- COURSES
INSERT INTO courses (id, user_id, title, description, created_at) VALUES
(7, 9, 'Web Design and Development I', 'HTML, CSS, JavaScript, PHP', '2025-09-29 16:28:06'),
(8, 9, 'Computer Networks', 'TCP/IP Protocol, Subnetting, IPv4/IPv6', '2025-09-29 16:28:42'),
(9, 9, 'Geographic Information Systems', 'Coordinate Systems, Spatial Analysis', '2025-09-29 16:29:21'),
(10, 9, 'Statistics and Probability', 'Measures of Central Tendency, Probability Distributions', '2025-09-29 16:30:09'),
(11, 12, 'Mathematics', '', '2025-09-29 16:39:44'),
(12, 12, 'Physics', '', '2025-09-29 16:40:02'),
(13, 12, 'English', '', '2025-09-29 16:40:09');

-- TASKS
INSERT INTO tasks (id, course_id, title, details, status, due_date, created_at, updated_at) VALUES
(11, 7, 'Finish UI/UX', 'Enhance UI/UX with animations', 'todo', '2025-10-05 23:59:00', '2025-09-29 16:31:07', '2025-09-29 16:31:07'),
(12, 10, 'Check Grades', 'Check Final Exam results and final grades', 'todo', '2025-10-02 08:30:00', '2025-09-29 16:32:01', '2025-09-29 16:32:01'),
(13, 7, 'CRUD Functionality', 'Fully implement CRUD functionality for final project', 'done', '2025-09-28 23:59:00', '2025-09-29 16:34:12', '2025-09-29 16:34:25'),
(14, 11, 'Submit Assignment', '', 'done', '2025-09-22 13:30:00', '2025-09-29 16:40:50', '2025-09-29 16:43:00'),
(15, 13, 'Presentation', '', 'done', '2025-09-25 10:30:00', '2025-09-29 16:41:16', '2025-09-29 16:42:52'),
(16, 12, 'Study for Final Exam', '', 'in_progress', '2025-10-06 09:00:00', '2025-09-29 16:41:49', '2025-09-29 16:42:38'),
(17, 11, 'Study for Final Exam', '', 'todo', '2025-10-10 14:00:00', '2025-09-29 16:42:21', '2025-09-29 16:42:21');
