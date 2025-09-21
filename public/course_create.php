<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Create Course</title>
</head>
<body>
  <h1>Create New Course</h1>
  <form action="course_process.php" method="POST">
    <input type="hidden" name="action" value="create">
    <div>
      <label for="title">Title:</label>
      <input type="text" name="title" id="title" required>
    </div>
    <div>
      <label for="description">Description:</label><br>
      <textarea name="description" id="description"></textarea>
    </div>
    <button type="submit">Save</button>
  </form>
  <p><a href="courses.php">Back to Courses</a></p>
</body>
</html>
