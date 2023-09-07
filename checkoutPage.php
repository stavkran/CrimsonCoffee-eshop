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
$usersCollection = $db->users;
$ordersCollection = $db->orders;

$firstName = '';
$lastName = '';
$email = '';

if (isset($_SESSION['loggedInUser'])) {
    $loggedInUserEmail = $_SESSION['loggedInUser'];
    $user = $usersCollection->findOne(['email' => $loggedInUserEmail]);
    if ($user) {
        $firstName = $user['firstname'];
        $lastName = $user['lastname'];
        $email = $user['email']; 
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $paymentMethod = $_POST['payment_method'];
    $deliveryAddress = $_POST['delivery_address'];
    $giftWrap = isset($_POST['gift_wrap']) ? $_POST['gift_wrap'] : 'No';


    $order = [
        'email' => $email, 
        'first_name' => $firstName, 
        'last_name' => $lastName,  
        'payment_method' => $paymentMethod,
        'delivery_address' => $deliveryAddress,
        'gift_wrap' => $giftWrap,
        'order_date' => date('Y-m-d H:i:s'),
        'items' => [],  
        'total_price' => 0, 
    ];


    $cartItemsCursor = $cartCollection->find(['email' => $email]);
    foreach ($cartItemsCursor as $item) {
        $order['items'][] = [
            'productname' => $item['productname'],
            'quantity' => $item['quantity'],
            'price' => $item['price'],
        ];
        $order['total_price'] += $item['price'] * $item['quantity'];
    }

    $ordersCollection->insertOne($order);


    $cartCollection->deleteMany(['email' => $email]);


    echo '<script>window.location.href = "orderConfirmation.php";</script>';
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta2/css/all.min.css" integrity="sha512-YWzhKL2whUzgiheMoBFwW8CKV4qpHQAEuvilg9FAn5VJUDwKZZxkJNuGM4XkWuk94WCrrwslk8yWNGmY1EduTA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro&display=swap" rel="stylesheet">
    <link type="text/css" rel="stylesheet" href="/CrimsonCoffeeEshop/css/header.css">
    <link type="text/css" rel="stylesheet" href="/CrimsonCoffeeEshop/css/signIn.css">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <style>

        .user-edit-container {
            margin-top: 25px;
            margin-bottom: 25px;
        }
        .user-edit-title {
            text-align: center;
            font-size: 1.5rem;
            padding-bottom: 10px;
        }
        .user-edit-form {
            max-width: 400px;
            margin: 0 auto;
        }
        .user-edit-form label {
            font-weight: bold;
        }
        .user-edit-form input[type="text"], .user-edit-form input[type="password"] {
            width: 100%;
            padding: 5px;
            margin-bottom: 10px;
        }
        .user-edit-form button[type="submit"] {
            background-color: black;
            color: white;
            border-radius: 10px;
            padding: 5px 15px;
        }
        .back-to-list-link {
            display: block;
            text-align: center;
            margin-top: 10px;
        }
        .message-container {
            text-align: center;
            margin-top: 20px;
        }

        .success-message {
            color: green;
            font-weight: bold;
        }


        .error-message {
            color: red;
            font-weight: bold;
        }
    </style>
</head>
<body>
<section class="vh-100">
    <div class="container h-100">
        <div class="row d-flex justify-content-center align-items-center h-100">
            <div class="col-lg-12 col-xl-11">
                <div class="card text-black" style="border-radius: 25px; margin-top: -20px;">
                    <div class="card-body p-md-5">
                        <div class="row justify-content-center">
                            <div class="col-md-10 col-lg-8 col-xl-7 order-2 order-lg-1">
                                <h1 class="text-center fw-bold mb-5">Checkout</h1>
                                <form method="post">
                                    <div class="mb-3">
                                        <label for="payment_method" class="form-label">Payment Method:</label>
                                        <select name="payment_method" id="payment_method" class="form-select" required>
                                            <option value="Pay on Delivery">Pay on Delivery</option>
                                            <option value="Credit Card">Credit Card</option>
                                            <option value="PayPal">PayPal</option>
                                        </select>
                                    </div>

                                    <div id="card_fields" style="display: none;">
                                        <div class="mb-3">
                                            <label for="card_number" class="form-label">Card Number:</label>
                                            <input type="text" name="card_number" class="form-control">
                                        </div>

                                        <div class="mb-3">
                                            <label for="bank_name" class="form-label">Bank Name:</label>
                                            <input type="text" name="bank_name" class="form-control">
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="delivery_address" class="form-label">Delivery Address:</label>
                                        <input type="text" name="delivery_address" class="form-control" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="first_name" class="form-label">First Name:</label>
                                        <input type="text" name="first_name" readonly class="form-control" value="<?= htmlspecialchars($firstName) ?>" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="last_name" class="form-label">Last Name:</label>
                                        <input type="text" name="last_name" readonly class="form-control" value="<?= htmlspecialchars($lastName) ?>" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="gift_wrap" class="form-check-label">Gift Wrap:</label>
                                        <input type="checkbox" name="gift_wrap" value="Yes" class="form-check-input">
                                    </div>

                                    <div class="mb-3">
                                        <input type="submit" value="Checkout" class="btn btn-primary">
                                        <a href="cartPage.php" class="btn btn-secondary">Cancel</a>
                                    </div>
                                </form>

                                <script>
                                
                                    const paymentMethodSelect = document.getElementById('payment_method');
                                    const cardFields = document.getElementById('card_fields');

                                    
                                    paymentMethodSelect.addEventListener('change', function () {
                                        const selectedPaymentMethod = paymentMethodSelect.value;

                                
                                        if (selectedPaymentMethod === 'Credit Card' || selectedPaymentMethod === 'PayPal') {
                                            cardFields.style.display = 'block';
                                        } else {
                                            cardFields.style.display = 'none';
                                        }
                                    });
                                </script>
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
