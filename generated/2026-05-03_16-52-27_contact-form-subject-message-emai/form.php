<?php
// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "contact_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message_status = "";

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $subject = mysqli_real_escape_string($conn, $_POST['subject']);
    $message = mysqli_real_escape_string($conn, $_POST['message']);
    $email_delivery = mysqli_real_escape_string($conn, $_POST['email_delivery']);

    $sql = "INSERT INTO contact_submissions (subject, message, email_delivery) VALUES ('$subject', '$message', '$email_delivery')";

    if ($conn->query($sql) === TRUE) {
        $message_status = "<div class='alert success'>Message sent successfully!</div>";
    } else {
        $message_status = "<div class='alert error'>Error: " . $conn->error . "</div>";
    }
}
?>
<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Contact Us</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }
        .container {
            background: #ffffff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 500px;
        }
        h2 {
            margin-top: 0;
            color: #333;
            text-align: center;
            font-weight: 600;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            color: #555;
            font-weight: 500;
        }
        input[type='text'],
        input[type='email'],
        textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            box-sizing: border-box;
            font-size: 16px;
            transition: border-color 0.3s ease;
        }
        input:focus, textarea:focus {
            outline: none;
            border-color: #4a90e2;
        }
        textarea {
            height: 120px;
            resize: vertical;
        }
        button {
            width: 100%;
            padding: 14px;
            background-color: #4a90e2;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #357abd;
        }
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 6px;
            text-align: center;
            font-weight: 500;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body>
    <div class='container'>
        <h2>Contact Form</h2>
        <?php echo $message_status; ?>
        <form action='' method='POST'>
            <div class='form-group'>
                <label for='subject'>Subject</label>
                <input type='text' id='subject' name='subject' required placeholder='What is this about?'>
            </div>
            <div class='form-group'>
                <label for='email_delivery'>Your Email Address</label>
                <input type='email' id='email_delivery' name='email_delivery' required placeholder='example@domain.com'>
            </div>
            <div class='form-group'>
                <label for='message'>Message</label>
                <textarea id='message' name='message' required placeholder='Enter your message here...'></textarea>
            </div>
            <button type='submit'>Send Message</button>
        </form>
    </div>
</body>
</html>
<?php $conn->close(); ?>