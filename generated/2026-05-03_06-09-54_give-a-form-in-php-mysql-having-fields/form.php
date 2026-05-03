<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "form_db";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $contact = $_POST['contact'];
    $address = $_POST['address'];
    
    $target_dir = "uploads/";
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    
    $file_name = time() . "_" . basename($_FILES["resume"]["name"]);
    $target_file = $target_dir . $file_name;
    $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    if ($_FILES["resume"]["size"] > 5000000) {
        $message = "File is too large.";
    } elseif ($file_type != "pdf" && $file_type != "doc" && $file_type != "docx") {
        $message = "Only PDF, DOC & DOCX files allowed.";
    } else {
        if (move_uploaded_file($_FILES["resume"]["tmp_name"], $target_file)) {
            $stmt = $conn->prepare("INSERT INTO submissions (name, contact, address, resume_path) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $name, $contact, $address, $target_file);
            if ($stmt->execute()) {
                $message = "Application submitted successfully!";
            } else {
                $message = "Database error: " . $conn->error;
            }
            $stmt->close();
        } else {
            $message = "Error uploading file.";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head><title>Job Application Form</title></head>
<body>
    <h2>Submit Your Details</h2>
    <?php if($message) echo "<p><strong>$message</strong></p>"; ?>
    <form action="" method="post" enctype="multipart/form-data">
        <p>Name: <br><input type="text" name="name" required></p>
        <p>Contact: <br><input type="text" name="contact" required></p>
        <p>Address: <br><textarea name="address" required></textarea></p>
        <p>Resume (PDF/DOC): <br><input type="file" name="resume" required></p>
        <button type="submit">Submit</button>
    </form>
</body>
</html>