<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$course_id = $_GET['course_id'] ?? null;
if (!$course_id) {
    die("No course selected.");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Upload Resource</title>
</head>
<body>
  <h1>Upload File</h1>
  <form action="upload_resource_process.php" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="course_id" value="<?= htmlspecialchars($course_id) ?>">
    <div>
      <label for="file">Choose file:</label>
      <input type="file" name="file" id="file" required>
    </div>
    <button type="submit">Upload</button>
  </form>
  <p><a href="resources.php?course_id=<?= htmlspecialchars($course_id) ?>">Back to Resources</a></p>
</body>
</html>
