<?php
$host = 'localhost';
$db   = 'store_db';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    $error = "Connection failed: " . $e->getMessage();
}

$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product = $_POST['product'] ?? '';
    $quantity = $_POST['quantity'] ?? '';
    $customer_name = $_POST['customer_name'] ?? '';
    $address = $_POST['address'] ?? '';
    $payment = $_POST['payment'] ?? '';

    if ($product && $quantity && $customer_name && $address && $payment) {
        $sql = "INSERT INTO orders (product_name, quantity, customer_name, address, payment_method) VALUES (?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        if ($stmt->execute([$product, $quantity, $customer_name, $address, $payment])) {
            $message = "<div style='color:green; padding:10px; border:1px solid green;'>Order placed successfully!</div>";
        } else {
            $message = "<div style='color:red;'>Something went wrong.</div>";
        }
    } else {
        $message = "<div style='color:red;'>Please fill all fields.</div>";
    }
}
?>
<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <title>Checkout - Order Form</title>
    <style>
        body { font-family: sans-serif; background: #f4f4f4; padding: 20px; }
        .container { max-width: 500px; background: #fff; padding: 20px; margin: auto; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        label { display: block; margin-top: 10px; font-weight: bold; }
        input, select, textarea { width: 100%; padding: 8px; margin-top: 5px; box-sizing: border-box; }
        button { margin-top: 20px; width: 100%; padding: 10px; background: #28a745; color: #fff; border: none; cursor: pointer; border-radius: 4px; }
        button:hover { background: #218838; }
    </style>
</head>
<body>
    <div class='container'>
        <h2>Checkout Form</h2>
        <?php echo $message; ?>
        <form method='POST'>
            <label>Product</label>
            <select name='product' required>
                <option value='Smartphone'>Smartphone - $500</option>
                <option value='Laptop'>Laptop - $1000</option>
                <option value='Headphones'>Headphones - $150</option>
            </select>

            <label>Quantity</label>
            <input type='number' name='quantity' min='1' value='1' required>

            <label>Full Name</label>
            <input type='text' name='customer_name' placeholder='John Doe' required>

            <label>Shipping Address</label>
            <textarea name='address' rows='3' required></textarea>

            <label>Payment Method</label>
            <select name='payment' required>
                <option value='Credit Card'>Credit Card</option>
                <option value='PayPal'>PayPal</option>
                <option value='Bank Transfer'>Bank Transfer</option>
            </select>

            <button type='submit'>Complete Order</button>
        </form>
    </div>
</body>
</html>