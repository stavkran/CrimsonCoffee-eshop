<?php
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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_to_cart'])) {
        $image = $_POST['image'];
        $productName = $_POST['productname'];
        $price = $_POST['price'];
        $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1; 

        $cartCollection->insertOne([
            "email" => $email,
            "image" => $image,
            "productname" => $productName,
            "price" => $price,
            "quantity" => $quantity
        ]);


        echo '<script>window.location.href = "productsPage.php";</script>';
        exit();
    } elseif (isset($_POST['add_to_wishlist'])) {
        $wishlistMessage = '';

        $existingWishlistItem = $wishlistCollection->findOne([
            "email" => $email,
            "productname" => $_POST['productname']
        ]);

        if ($existingWishlistItem) {

            $wishlistMessage = "Product already exists in wishlist";
        } else {

            $wishlistCollection->insertOne([
                "email" => $email,
                "image" => $_POST['image'],
                "productname" => $_POST['productname'],
                "price" => $_POST['price']
            ]);


            $wishlistMessage = "Product successfully added to wishlist";
        }
    }
}


$cursor = $productsCollection->find();

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
<div class="mw-100" style="margin-top: 30px; margin-bottom: 50px;">
    <center><h1 style="margin-top:10px; margin-bottom: 20px;">Accessories</h1></center>
    <?php
    if (!empty($wishlistMessage)) {
        echo '<div class="alert alert-success text-center">' . $wishlistMessage . '</div>';
    }
    ?>
    <div class="d-flex flex-wrap justify-content-center" id="productscard">
        <?php foreach ($cursor as $document): ?>
            <?php if ($document['category'] === 'Accessories'): ?>
                <div class="card mx-2 mb-3" style="width: 18rem;">
                    <form method="POST">
                        <?php
                        $imageData = $document['image']->getData();
                        $imageMimeType = $document['image']->getType();
                        $base64Image = base64_encode($imageData);
                        $dataUri = 'data:' . $imageMimeType . ';base64,' . $base64Image;
                        ?>

                        <img src="<?= $dataUri ?>" class="card-img-top">
                        <div class="card-body">
                            <h5 class="card-title"><?= $document['name'] ?></h5>
                            <label for="quantity">Quantity:</label>
                            <input type="number" name="quantity" value="1" min="1">
                            <p class="card-text">$<?= $document['price'] ?></p>
                            <input type="hidden" name="image" value="<?= $dataUri ?>">
                            <input type="hidden" name="productname" value="<?= $document['name'] ?>">
                            <input type="hidden" name="price" value="<?= $document['price'] ?>">
                            <input type="submit" name="add_to_cart" value="Add to Cart" style="background-color: black; color: white; border-radius: 10px; border-color: black; padding:10px; margin-right:5px;">
                            <input type="submit" name="add_to_wishlist" value="♡" style="background-color: #8F1111; color: white; border-radius: 10px; border-color: black; padding:10px; padding-left:15px; padding-right:15px; font-weight:bold;">
                        </div>
                    </form>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
</div>


</body>
</html>

