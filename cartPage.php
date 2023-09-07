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
$wishlistCollection = $db->wishlist;

if (isset($_SESSION['loggedInUser'])) {
    $email = $_SESSION['loggedInUser'];
} else {
    $email = 'No Account User';
}


$cartItemsCursor = $cartCollection->find(['email' => $email]);
$cartItems = iterator_to_array($cartItemsCursor);


$totalPrice = 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['remove_item'])) {
        $itemId = $_POST['remove_item'];
        $cartCollection->deleteOne(['_id' => new MongoDB\BSON\ObjectId($itemId)]);
  
        echo '<script>window.location.href = "cartPage.php";</script>';
        exit();
    }
}


foreach ($cartItems as $item) {
    $totalPrice += $item['price'] * $item['quantity'];
}


$cartExpirationTime = strtotime("+24 hours");
$currentTimestamp = time();
$remainingTime = $cartExpirationTime - $currentTimestamp;


$remainingHours = floor($remainingTime / 3600);
$remainingMinutes = floor(($remainingTime % 3600) / 60);


$expirationMessage = "Be careful! Your products will stay in the cart for only 24 hours! Hurry up and make your purchase. The time you have left is: $remainingHours hours and $remainingMinutes minutes.";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Shopping Cart</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta2/css/all.min.css" integrity="sha512-YWzhKL2whUzgiheMoBFwW8CKV4qpHQAEuvilg9FAn5VJUDwKZZxkJNuGM4XkWuk94WCrrwslk8yWNGmY1EduTA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro&display=swap" rel="stylesheet">
    <link type="text/css" rel="stylesheet" href="/CrimsonCoffeeEshop/css/header.css">
    <link type="text/css" rel="stylesheet" href="/CrimsonCoffeeEshop/css/signIn.css">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script>
        function updateRemainingTime() {
            var expirationTime = <?= $cartExpirationTime ?> * 1000; 
            var currentTime = new Date().getTime();
            var remainingTime = expirationTime - currentTime;
            
            var seconds = Math.floor((remainingTime / 1000) % 60);
            var minutes = Math.floor((remainingTime / 1000 / 60) % 60);
            var hours = Math.floor((remainingTime / (1000 * 60 * 60)) % 24);
            
            document.getElementById("remainingTime").innerHTML = "The time you have left is: <br>" + hours + " hours, " + minutes + " minutes, " + seconds + " seconds";
            
            if (remainingTime <= 0) {
                clearInterval(intervalId);
                // Hide the warning message or take action when the cart expires
                // Example: document.getElementById("cartWarning").style.display = "none";
            }
        }
        
        var intervalId = setInterval(updateRemainingTime, 1000); 
    </script>
</head>
<body>
    <section class="vh-100" style="margin-top: 5px; margin-bottom: 25px;">
    <div class="container h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col-lg-12 col-xl-11">
                    <div class="card text-black" style="border-radius: 25px; margin-top: -20px;">
                        <div class="card-body p-md-5">
                            <div class="row justify-content-center">
                                <div class="col-md-10 col-lg-8 col-xl-7 order-2 order-lg-1">
                                    <h1 class="text-center fw-bold mb-5" style="padding-bottom:5px">Your Shopping Cart</h1>
                                    
                                    <strong><p class="text-center text-danger" id="cartWarning">Be careful! Your products will stay in the cart for only 24 hours! <br>Hurry up and make your purchase.<br> <span id="remainingTime"></span></p></strong>
                                    
                                    <?php
                                    $cartItemCount = count($cartItems);
                                    if ($cartItemCount === 0):
                                    ?>
                                        <p>Your cart is empty. You cannot proceed to checkout.</p>
                                    <?php else: ?>
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Product</th>
                                                <th>Price</th>
                                                <th>Quantity</th>
                                                <th>Total</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($cartItems as $item): ?>
                                            <tr>
                                                <td><?= $item['productname'] ?></td>
                                                <td>$<?= $item['price'] ?></td>
                                                <td><?= $item['quantity'] ?></td>
                                                <td>$<?= $item['price'] * $item['quantity'] ?></td>
                                                <td>
                                                    <form method="post">
                                                        <input type="hidden" name="remove_item" value="<?= $item['_id'] ?>">
                                                        <button type="submit" class="btn btn-danger">Remove</button>
                                                    </form>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                    <p style="font-size: 1.1em; font-weight:bold;">Total Price: $<?= $totalPrice ?></p>
                                    <?php if ($cartItemCount > 0): ?>
                                        <a href="checkoutPage.php" class="btn btn-success">Proceed to Checkout</a>
                                    <?php endif; ?>

                                    <a href="wishlistPage.php" class="btn btn-primary" style="background-color: black; border-color:black;">Wishlist</a>
                                    <?php endif; ?>

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
