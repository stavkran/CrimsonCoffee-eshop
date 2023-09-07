<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include('include/header.php');

if (isset($_GET['q'])) {
    $searchQuery = $_GET['q'];


    require_once __DIR__ . '/vendor/autoload.php';
    $mongoClient = new MongoDB\Client("mongodb://localhost:27017");
    $db = $mongoClient->crimsonCoffee;
    $productsCollection = $db->products;


    $indexName = 'productname_text';
    $existingIndexes = $productsCollection->listIndexes();

    $indexExists = false;
    foreach ($existingIndexes as $existingIndex) {
        if ($existingIndex['name'] === $indexName) {
            $indexExists = true;
            break;
        }
    }


    if ($indexExists) {
        $productsCollection->dropIndex($indexName);
    }

    $indexKeys = [
        'productname' => 'text',
        'description' => 'text',
    ];

    $indexOptions = [
        'background' => true,
        'weights' => [
            'productname' => 1,
            'description' => 1,
        ],
        'default_language' => 'english',
        'language_override' => 'language',
        'textIndexVersion' => 3,
    ];

    $indexName = 'productname_description_text';

    $productsCollection->createIndex($indexKeys, $indexOptions, ['name' => $indexName]);

    $query = [
        '$text' => [
            '$search' => $searchQuery,
        ],
    ];


    $countCursor = $productsCollection->find($query);
    $count = count(iterator_to_array($countCursor));


    $cursor = $productsCollection->find($query);

    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Crimson Coffee | Search Results</title>

    </head>
    <body>
        <h1>Search Results</h1>

        <?php if ($count === 0): ?>
            <p>No results found.</p>
        <?php else: ?>
            <p><?= $count ?> result(s) found:</p>
            <ul>
                <?php foreach ($cursor as $product): ?>
                    <li>
                        <?php if (isset($product['productname'])): ?>
                            <a href="/CrimsonCoffeeEshop/productDetails.php?id=<?= $product['_id'] ?>">
                                <?= $product['productname'] ?>
                            </a>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

    </body>
    </html>
    <?php
} else {
    header('Location: /CrimsonCoffeeEshop/homepage.php');
    exit();
}
?>
