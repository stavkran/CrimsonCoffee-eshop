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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_item'])) {
    $itemId = $_POST['remove_item'];
    $wishlistCollection->deleteOne(['_id' => new MongoDB\BSON\ObjectId($itemId)]);
    echo '<script>window.location.href = "wishlistPage.php";</script>';
    exit();
}

$wishlistItemsCursor = $wishlistCollection->find(['email' => $email]);
$wishlistItems = iterator_to_array($wishlistItemsCursor);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wishlist</title>
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
    <section class="vh-100" style="margin-top: 5px; margin-bottom: 25px;">
        <div class="container h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col-lg-12 col-xl-11">
                    <div class="card text-black" style="border-radius: 25px; margin-top: -20px;">
                        <div class="card-body p-md-5">
                            <div class="row justify-content-center">
                                <div class="col-md-10 col-lg-8 col-xl-7 order-2 order-lg-1">
                                <h1>Your Wishlist</h1>
                                <?php if (count($wishlistItems) === 0): ?>
                                    <p>Your wishlist is empty.</p>
                                <?php else: ?>
                                    <ul>
                                        <?php foreach ($wishlistItems as $item): ?>
                                            <li>
                                                <strong>Email:</strong> <?= $item['email'] ?><br>
                                                <strong>Product Name:</strong> <?= $item['productname'] ?><br>
                                                <strong>Price:</strong> $<?= $item['price'] ?><br>
                                                <img src="<?= $item['image'] ?>" alt="Product Image" style="max-width: 200px;"><br>
                                                <form method="post">
                                                    <input type="hidden" name="remove_item" value="<?= $item['_id'] ?>">
                                                    <button type="submit">Remove</button>
                                                </form>
                                            </li>
                                            <hr>
                                        <?php endforeach; ?>
                                    </ul>
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
