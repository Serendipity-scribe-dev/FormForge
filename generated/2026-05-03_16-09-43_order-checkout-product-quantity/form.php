<?php
// Database configuration
$host = "localhost";
$username = "root";
$password = "";
$dbname = "shop_db";

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = "";

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product = $_POST['product'];
    $quantity = $_POST['quantity'];
    $address = $_POST['address'];
    $payment = $_POST['payment'];

    $stmt = $conn->prepare("INSERT INTO orders (product_name, quantity, address, payment_method) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("siss", $product, $quantity, $address, $payment);

    if ($stmt->execute()) {
        $message = "<p style='color: green;'>Order placed successfully!</p>";
    } else {
        $message = "<p style='color: red;'>Error: " . $conn->error . "</p>";
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <title>Checkout - Product Order</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px; }
        .container { max-width: 500px; background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); margin: auto; }
        h2 { text-align: center; color: #333; }
        label { display: block; margin: 10px 0 5px; }
        input, select, textarea { width: 100%; padding: 10px; margin-bottom: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        button { width: 100%; padding: 10px; background-color: #28a745; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 16px; }
        button:hover { background-color: #218838; }
    </style>
</head>
<body>
    <div class='container'>
        <h2>Order Checkout</h2>
        <?php echo $message; ?>
        <form method='POST' action=''>
            <label>Select Product:</label>
            <select name='product' required>
                <option value='Smartphone'>Smartphone</option>
                <option value='Laptop'>Laptop</option>
                <option value='Headphones'>Headphones</option>
            </select>

            <label>Quantity:</label>
            <input type='number' name='quantity' min='1' value='1' required>

            <label>Shipping Address:</label>
            <textarea name='address' rows='4' required></textarea>

            <label>Payment Method:</label>
            <select name='payment' required>
                <option value='Credit Card'>Credit Card</option>
                <option value='PayPal'>PayPal</option>
                <option value='Bank Transfer'>Bank Transfer</option>
            </select>

            <button type='submit'>Complete Purchase</button>
        </form>
    </div>
</body>
</html>