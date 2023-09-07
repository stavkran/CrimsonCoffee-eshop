<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include('include/header.php');

require_once __DIR__ . '/vendor/autoload.php';


$mongoClient = new MongoDB\Client("mongodb://localhost:27017");
$db = $mongoClient->crimsonCoffee;
$productsCollection = $db->products;
$cartCollection = $db->cart;
$ordersCollection = $db->orders;


if (isset($_SESSION['loggedInUser'])) {
    $email = $_SESSION['loggedInUser'];
} else {
    $email = 'No Account User';
}

$purchaseHistoryCursor = $ordersCollection->find(['email' => $email]);
$purchaseHistory = iterator_to_array($purchaseHistoryCursor);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crimson Coffee</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta2/css/all.min.css" integrity="sha512-YWzhKL2whUzgiheMoBFwW8CKV4qpHQAEuvilg9FAn5VJUDwKZZxkJNuGM4XkWuk94WCrrwslk8yWNGmY1EduTA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro&display=swap" rel="stylesheet">

    <link type="text/css" rel="stylesheet" href="/CrimsonCoffeeEshop/css/styles.css">
    <link type="text/css" rel="stylesheet" href="/CrimsonCoffeeEshop/css/hp_section1.css">
    <link type="text/css" rel="stylesheet" href="/CrimsonCoffeeEshop/css/hp_section2.css">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
<body>
    <center style="margin-top:30px;">
    <h1>Purchase History</h1>

    <?php if (count($purchaseHistory) === 0): ?>
        <p>No purchase history found.</p>
    <?php else: ?>
        <ul style="list-style-type: none;">
        <?php foreach ($purchaseHistory as $order): ?>
            <li>
                <strong>Order ID:</strong> <?= $order['_id'] ?><br>
                <strong>Date Ordered:</strong> <?= $order['order_date'] ?><br>
                <strong>Total Amount:</strong> $<?= number_format($order['total_price'], 2) ?><br>
                <strong>Shipping Address:</strong> <?= $order['delivery_address'] ?><br>
                <strong>Payment Method:</strong> <?= $order['payment_method'] ?><br>
                <strong>Gift Wrap:</strong> <?= $order['gift_wrap'] === 'Yes' ? 'Yes' : 'No' ?><br>
                
                <strong>Items:</strong>
                <ul style="list-style-type: none;">
                    <?php foreach ($order['items'] as $item): ?>
                        <li>
                            <strong>Product Name:</strong> <?= $item['productname'] ?><br>
                            <strong>Quantity:</strong> <?= $item['quantity'] ?><br>
                            <strong>Price per Unit:</strong> $<?= number_format($item['price'], 2) ?><br>
                            <strong>Total Price for Item:</strong> $<?= number_format($order['total_price'], 2) ?><br>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </li>
            <br>
            <hr>
        <?php endforeach; ?>
        </ul>
        <a style="margin-bottom:50px; background-color:black; border-color:black;" href="reviewProducts.php?id=<?= $order['_id'] ?>" class="btn btn-primary">Review Purchase Products</a>
    <?php endif; ?>

    </center>
</body>
</html>

<div class="footer" style="padding: 20px 0;position: absolute;bottom: -50;width: 100%;z-index: 2;">
    <?php
    include('include/footer.html');
    ?>
</div>