<?php
$host = 'localhost';
$db   = 'job_portal';
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass);
if ($conn->connect_error) { die('Connection failed: ' . $conn->connect_error); }
$conn->query("CREATE DATABASE IF NOT EXISTS $db");
$conn->select_db($db);

$message = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['full_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $skills = isset($_POST['skills']) ? implode(', ', $_POST['skills']) : '';
    $cover_letter = $_POST['cover_letter'];

    $target_dir = 'uploads/';
    if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);
    
    $file_name = time() . '_' . basename($_FILES['resume']['name']);
    $target_file = $target_dir . $file_name;
    $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    if ($file_type != 'pdf' && $file_type != 'doc' && $file_type != 'docx') {
        $message = '<p style="color:red">Error: Only PDF, DOC & DOCX files are allowed.</p>';
    } else {
        if (move_uploaded_file($_FILES['resume']['tmp_name'], $target_file)) {
            $stmt = $conn->prepare("INSERT INTO applications (full_name, email, phone, skills, cover_letter, resume_path) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param('ssssss', $name, $email, $phone, $skills, $cover_letter, $target_file);
            if ($stmt->execute()) {
                $message = '<p style="color:green">Application submitted successfully!</p>';
            } else {
                $message = '<p style="color:red">Database Error: ' . $conn->error . '</p>';
            }
            $stmt->close();
        } else {
            $message = '<p style="color:red">Error uploading file.</p>';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Job Application Form</title>
    <style>
        body { font-family: sans-serif; max-width: 600px; margin: 20px auto; padding: 20px; border: 1px solid #ccc; }
        input, textarea { width: 100%; margin-bottom: 10px; }
        .checkbox-group { margin-bottom: 15px; }
        .checkbox-group input { width: auto; }
    </style>
</head>
<body>
    <h2>Apply for Job</h2>
    <?php echo $message; ?>
    <form action="" method="POST" enctype="multipart/form-data">
        <label>Full Name:</label><br>
        <input type="text" name="full_name" required>

        <label>Email:</label><br>
        <input type="email" name="email" required>

        <label>Phone:</label><br>
        <input type="text" name="phone" required>

        <label>Skills:</label><div class="checkbox-group">
            <input type="checkbox" name="skills[]" value="PHP"> PHP 
            <input type="checkbox" name="skills[]" value="JavaScript"> JavaScript 
            <input type="checkbox" name="skills[]" value="Python"> Python 
            <input type="checkbox" name="skills[]" value="Design"> Design
        </div>

        <label>Cover Letter:</label><br>
        <textarea name="cover_letter" rows="5" required></textarea>

        <label>Resume (PDF/DOC):</label><br>
        <input type="file" name="resume" required>

        <button type="submit">Submit Application</button>
    </form>
</body>
</html>