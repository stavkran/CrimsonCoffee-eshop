<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include('include/header.php');

require_once __DIR__ . '/vendor/autoload.php';

$mongoClient = new MongoDB\Client("mongodb://localhost:27017");
$db = $mongoClient->crimsonCoffee;
$productsCollection = $db->products;
$productReviewsCollection = $db->productReviews; 
$ordersCollection = $db->orders;

if (isset($_SESSION['loggedInUser'])) {
    $email = $_SESSION['loggedInUser'];
} else {
    header('Location: login.php');
    exit();
}

$submitMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rating = $_POST['rating'];
    $review = $_POST['review'];

    $reviewDocument = [
        'email' => $email,
        'rating' => $rating,
        'review' => $review,
        'timestamp' => new MongoDB\BSON\UTCDateTime()
    ];

    $insertResult = $productReviewsCollection->insertOne($reviewDocument);

    if ($insertResult->getInsertedCount() === 1) {
        $submitMessage = 'Review submitted successfully.';
        echo '<script>window.location.href = "reviewProducts.php";</script>';
        exit();
    } else {
        $submitMessage = 'Error adding the review.';
    }
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
    <link type="text/css" rel="stylesheet" href="/CrimsonCoffeeEshop/css/hp_section2.css">\
    <link type="text/css" rel="stylesheet" href="/CrimsonCoffeeEshop/css/hp_sections3_4.css">

    <script>
        function showMessage(message) {
            document.getElementById('message').textContent = message;
        }

        <?php if (!empty($submitMessage)) : ?>
            showMessage("<?php echo $submitMessage; ?>");
        <?php endif; ?>
    </script>
</head>
<body>
    <center style="margin-top:30px;">
        <h1>Review Purchased Products</h1>

        <?php if (count($purchaseHistory) === 0): ?>
            <p>You have no purchase history to review products.</p>
        <?php else: ?>
            <?php foreach ($purchaseHistory as $order): ?>
                <h3>Purchase Details</h3>
                <p><strong>Order ID:</strong> <?= $order['_id'] ?></p>
                <p><strong>Date Ordered:</strong> <?= $order['order_date'] ?></p>
                <p><strong>Total Amount:</strong> $<?= number_format($order['total_price'], 2) ?></p>
                <p><strong>Payment Method:</strong> <?= $order['payment_method'] ?></p>

                <h3>Products in this Purchase</h3>
                <?php foreach ($order['items'] as $item): ?>
                    <div class="product-review">
                        <h4><?= $item['productname'] ?></h4>
                        <p><strong>Price:</strong> $<?= number_format($item['price'], 2) ?></p>
                        <form method="POST" action="reviewProducts.php">
                            <label for="rating">Rating:</label>
                            <select name="rating" required>
                                <option value="1">1 (Poor)</option>
                                <option value="2">2 (Fair)</option>
                                <option value="3">3 (Good)</option>
                                <option value="4">4 (Very Good)</option>
                                <option value="5">5 (Excellent)</option>
                            </select>
                            <label for="review">Review:</label>
                            <textarea name="review" required></textarea>
                            <input type="submit" value="Submit Review">
                        </form>
                    </div>
                    <hr>
                <?php endforeach; ?>
            <?php endforeach; ?>
        <?php endif; ?>

        <p id="message"><?= $submitMessage ?></p> 
    </center>
</body>
</html>
