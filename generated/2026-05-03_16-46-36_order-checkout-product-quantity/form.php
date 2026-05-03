<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "checkout_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product = $_POST['product'];
    $quantity = $_POST['quantity'];
    $address = $_POST['address'];
    $payment = $_POST['payment'];

    $sql = "INSERT INTO orders (product, quantity, address, payment) VALUES ('$product', '$quantity', '$address', '$payment')";

    if ($conn->query($sql) === TRUE) {
        $message = "Order placed successfully!";
    } else {
        $message = "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Checkout Form</title>
    <style>
        body { font-family: sans-serif; margin: 20px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; }
        input, textarea, select { width: 300px; padding: 8px; }
        input[type="submit"] { width: auto; cursor: pointer; background: #28a745; color: white; border: none; }
    </style>
</head>
<body>
    <h2>Product Checkout</h2>
    <?php if($message) echo "<p><strong>$message</strong></p>"; ?>
    <form method="POST" action="">
        <div class="form-group">
            <label>Product Name</label>
            <input type="text" name="product" required>
        </div>
        <div class="form-group">
            <label>Quantity</label>
            <input type="number" name="quantity" required>
        </div>
        <div class="form-group">
            <label>Shipping Address</label>
            <textarea name="address" required></textarea>
        </div>
        <div class="form-group">
            <label>Payment Method</label>
            <select name="payment" required>
                <option value="Credit Card">Credit Card</option>
                <option value="PayPal">PayPal</option>
                <option value="Bank Transfer">Bank Transfer</option>
            </select>
        </div>
        <input type="submit" value="Submit Order">
    </form>
</body>
</html>