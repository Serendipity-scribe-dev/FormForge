<?php
// Database configuration
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'contact_db';

// Create connection
$conn = new mysqli($host, $user, $pass, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$status_message = "";

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $subject = mysqli_real_escape_string($conn, $_POST['subject']);
    $message = mysqli_real_escape_string($conn, $_POST['message']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);

    $sql = "INSERT INTO contact_messages (subject, message, email) VALUES ('$subject', '$message', '$email')";

    if ($conn->query($sql) === TRUE) {
        $status_message = "<div class='success'>Message sent successfully!</div>";
    } else {
        $status_message = "<div class='error'>Error: " . $conn->error . "</div>";
    }
}
?>
<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Contact Form</title>
    <style>
        :root {
            --primary: #4f46e5;
            --primary-hover: #4338ca;
            --bg: #f8fafc;
            --card: #ffffff;
            --text: #1e293b;
        }
        body {
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            background-color: var(--bg);
            color: var(--text);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }
        .container {
            background: var(--card);
            padding: 2.5rem;
            border-radius: 1rem;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 450px;
        }
        h2 {
            margin-top: 0;
            margin-bottom: 1.5rem;
            font-size: 1.5rem;
            font-weight: 700;
            color: #111827;
            text-align: center;
        }
        .form-group {
            margin-bottom: 1.25rem;
        }
        label {
            display: block;
            margin-bottom: 0.5rem;
            font-size: 0.875rem;
            font-weight: 500;
        }
        input, textarea {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
            font-size: 1rem;
            box-sizing: border-box;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        input:focus, textarea:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }
        textarea {
            resize: vertical;
            min-height: 120px;
        }
        button {
            width: 100%;
            background-color: var(--primary);
            color: white;
            padding: 0.75rem;
            border: none;
            border-radius: 0.5rem;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.2s;
        }
        button:hover {
            background-color: var(--primary-hover);
        }
        .success {
            background-color: #dcfce7;
            color: #166534;
            padding: 1rem;
            border-radius: 0.5rem;
            margin-bottom: 1rem;
            text-align: center;
            font-size: 0.875rem;
        }
        .error {
            background-color: #fee2e2;
            color: #991b1b;
            padding: 1rem;
            border-radius: 0.5rem;
            margin-bottom: 1rem;
            text-align: center;
            font-size: 0.875rem;
        }
    </style>
</head>
<body>
    <div class='container'>
        <h2>Send a Message</h2>
        <?php echo $status_message; ?>
        <form action='' method='POST'>
            <div class='form-group'>
                <label for='email'>Email Address</label>
                <input type='email' id='email' name='email' required placeholder='you@example.com'>
            </div>
            <div class='form-group'>
                <label for='subject'>Subject</label>
                <input type='text' id='subject' name='subject' required placeholder='How can we help?'>
            </div>
            <div class='form-group'>
                <label for='message'>Message</label>
                <textarea id='message' name='message' required placeholder='Your message here...'></textarea>
            </div>
            <button type='submit'>Send Message</button>
        </form>
    </div>
</body>
</html>