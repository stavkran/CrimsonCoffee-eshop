<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include('include/header.php');

require_once __DIR__ . '/vendor/autoload.php';

$mongoClient = new MongoDB\Client("mongodb://localhost:27017");
$db = $mongoClient->crimsonCoffee;
$usersCollection = $db->users;
$ordersCollection = $db->orders;


$firstName = '';
$email = '';


if (isset($_SESSION['loggedInUser'])) {
    $loggedInUserEmail = $_SESSION['loggedInUser'];
    $user = $usersCollection->findOne(['email' => $loggedInUserEmail]);
    if ($user) {
        $firstName = $user['firstname'];
        $email = $user['email'];
    }
}


$order = $ordersCollection->findOne(
    ['email' => $email],
    ['sort' => ['order_date' => -1]]
);

if (!$order) {

    echo "No order found.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta2/css/all.min.css" integrity="sha512-YWzhKL2whUzgiheMoBFwW8CKV4qpHQAEuvilg9FAn5VJUDwKZZxkJNuGM4XkWuk94WCrrwslk8yWNGmY1EduTA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro&display=swap" rel="stylesheet">
    <link type="text/css" rel="stylesheet" href="/CrimsonCoffeeEshop/css/header.css">
    <link type="text/css" rel="stylesheet" href="/CrimsonCoffeeEshop/css/signIn.css">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <style>
    
        <style>
    
        body {
            font-family: Arial, sans-serif;
        }
        .confirmation-container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #FFF; 
        }
        .confirmation-heading {
            color: #8F1111; 
            font-size: 24px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 20px;
        }
        .order-details {
            margin-bottom: 20px;
        }
        .order-details p {
            color: #333; 
            font-size: 16px;
            margin: 0;
        }
        .tracking-link {
            color: #8F1111;
            text-decoration: none;
        }
        .tracking-link:hover {
            text-decoration: underline;
        }
        .thank-you-message {
            color: #333; 
            font-size: 16px;
        }
    </style>
</head>
<body>
<section class="vh-100">
    <div class="container h-100" style="margin-top: 25px; margin-bottom:20px">
        <div class="row d-flex justify-content-center align-items-center h-100">
            <div class="col-lg-12 col-xl-11">
                <div class="card text-black" style="border-radius: 25px; margin-top: -20px;">
                    <div class="card-body p-md-5 order-confirmation-container">
                        <div class="row justify-content-center">
                            <div class="col-md-10 col-lg-8 col-xl-7 order-2 order-lg-1">
                                <h1 class="text-center fw-bold mb-5 order-confirmation-title">Your Order Confirmation</h1>
                                <div class="order-details">
                                    <p>Your order from Crimson Coffee is confirmed!</p>
                                    <p>Hi <?= htmlspecialchars($firstName) ?>,</p>
                                    <p>Thank you for your order from Crimson Coffee! We're excited to get your order started and deliver you some of the finest coffee around.</p>
                                    <p>Here are the details of your order:</p>
                                    <ul>
                                        <li>Order number: <?= $order['_id'] ?></li>
                                        <li>Date ordered: <?= $order['order_date'] ?></li>
                                        <li>Items ordered: 
                                            <ul>
                                                <?php foreach ($order['items'] as $item): ?>
                                                    <li><?= $item['productname'] ?> (Quantity: <?= $item['quantity'] ?>)</li>
                                                <?php endforeach; ?>
                                            </ul>
                                        </li>
                                        <li>Total amount: $<?= $order['total_price'] ?></li>
                                        <li>Shipping address: <?= $order['delivery_address'] ?></li>
                                    </ul>
                                    <p>We'll be shipping your order out as soon as possible. You can track your order here: <?php $trackingNumber = mt_rand(100000, 999999); ?> </p>
                                    <p>In the meantime, enjoy a free cup of coffee on us! Just show this email at any Crimson Coffee location.</p>
                                    <p class="thank-you-message">Thanks again for your order!</p>
                                    <p>The Crimson Coffee Team</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
</body>
</html>
