<?php
$host = 'localhost';
$db   = 'checkout_system';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product = $conn->real_escape_string($_POST['product']);
    $quantity = (int)$_POST['quantity'];
    $customer_name = $conn->real_escape_string($_POST['customer_name']);
    $email = $conn->real_escape_string($_POST['email']);
    $address = $conn->real_escape_string($_POST['address']);
    $payment = $conn->real_escape_string($_POST['payment_method']);

    $sql = "INSERT INTO orders (product_name, quantity, customer_name, email, address, payment_method) 
            VALUES ('$product', $quantity, '$customer_name', '$email', '$address', '$payment')";

    if ($conn->query($sql) === TRUE) {
        $message = "<div style='color: green; padding: 10px; border: 1px solid green;'>Order placed successfully! Order ID: " . $conn->insert_id . "</div>";
    } else {
        $message = "<div style='color: red;'>Error: " . $conn->error . "</div>";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Checkout Form</title>
    <style>
        body { font-family: sans-serif; background: #f4f4f4; padding: 20px; }
        .container { max-width: 500px; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); margin: auto; }
        label { display: block; margin-top: 10px; font-weight: bold; }
        input, select, textarea { width: 100%; padding: 8px; margin-top: 5px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        button { margin-top: 20px; width: 100%; padding: 10px; background: #28a745; color: white; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background: #218838; }
    </style>
</head>
<body>
<div class="container">
    <h2>Checkout / Order Form</h2>
    <?php echo $message; ?>
    <form method="POST" action="">
        <label>Product</label>
        <select name="product" required>
            <option value="Smartphone">Smartphone - $500</option>
            <option value="Laptop">Laptop - $1000</option>
            <option value="Headphones">Headphones - $150</option>
        </select>

        <label>Quantity</label>
        <input type="number" name="quantity" min="1" value="1" required>

        <label>Full Name</label>
        <input type="text" name="customer_name" required>

        <label>Email</label>
        <input type="email" name="email" required>

        <label>Shipping Address</label>
        <textarea name="address" rows="3" required></textarea>

        <label>Payment Method</label>
        <select name="payment_method" required>
            <option value="Credit Card">Credit Card</option>
            <option value="PayPal">PayPal</option>
            <option value="Bank Transfer">Bank Transfer</option>
        </select>

        <button type="submit">Place Order</button>
    </form>
</div>
</body>
</html>