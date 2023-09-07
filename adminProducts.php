<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include('include/adminHeader.php');

require_once __DIR__ . '/vendor/autoload.php';


$mongoClient = new MongoDB\Client("mongodb://localhost:27017");
$db = $mongoClient->crimsonCoffee;
$collection = $db->products;

function isEditModeEnabled() {
    return isset($_GET['edit']) && $_GET['edit'] === 'true';
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['addProduct'])) {

    if (isset($_FILES['productImage']) && $_FILES['productImage']['error'] === UPLOAD_ERR_OK) {
     
        $uploadDirectory = __DIR__ . '/images/';


        $uploadedFileName = uniqid() . '_' . $_FILES['productImage']['name'];

    
        $uploadFilePath = $uploadDirectory . $uploadedFileName;

      
        if (move_uploaded_file($_FILES['productImage']['tmp_name'], $uploadFilePath)) {
        
            $newProduct = [
                'image' => new MongoDB\BSON\Binary(file_get_contents($uploadFilePath), MongoDB\BSON\Binary::TYPE_GENERIC),
                'name' => $_POST['productName'],
                'description' => $_POST['productDescription'],
                'price' => (float)$_POST['productPrice'],
                'category' => $_POST['productCategory']
            ];

         
            $collection->insertOne($newProduct);
        } else {
           
            echo 'Error uploading the file.';
        }
    } else {
    
        echo 'No file uploaded.';
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['deleteProduct'])) {
    $productId = $_POST['productId'];

    
    $deleteResult = $collection->deleteOne(['_id' => new MongoDB\BSON\ObjectId($productId)]);

    if ($deleteResult->getDeletedCount() > 0) {

    }
}


$productsCursor = $collection->find();


$productsArray = iterator_to_array($productsCursor);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crimson Coffee | Admin Products Edit</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta2/css/all.min.css" integrity="sha512-YWzhKL2whUzgiheMoBFwW8CKV4qpHQAEuvilg9FAn5VJUDwKZZxkJNuGM4XkWuk94WCrrwslk8yWNGmY1EduTA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro&display=swap" rel="stylesheet">

    <!-- Add your CSS stylesheets -->
    <link type="text/css" rel="stylesheet" href="/CrimsonCoffeeEshop/css/adminProducts.css">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
<body>
    <section class="vh-100" style="margin-top: 25px; margin-bottom: 25px;">
        <div class="container h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col-lg-12 col-xl-11">
                    <div class="card text-black" style="border-radius: 25px; margin-top: -20px;">
                        <div class="card-body p-md-5">
                            <div class="row justify-content-center">
                                <div class="col-md-10 col-lg-8 col-xl-7 order-2 order-lg-1">
                                    <h1 class="text-center fw-bold mb-5">Admin Products</h1>

                                    <!-- Product List - Initially Visible -->
                                    <div id="productList">
                                        <?php if (count($productsArray) === 0) { ?>
                                            <p class="text-center" style="font-size: 1.3em;">Sorry! No products available</p>
                                        <?php } else { ?>
                                            <table class="table table-striped" style="width: 100%;">
                                                <thead>
                                                    <tr>
                                                        <th>Image</th>
                                                        <th>Name</th>
                                                        <th>Description</th>
                                                        <th>Price</th>
                                                        <th>Category</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($productsArray as $product) { ?>
                                                        <tr>
                                                            <td>
                                                                <?php
                                                                $imageData = $product['image']->getData();
                                                                $imageMimeType = $product['image']->getType();
                                                                $base64Image = base64_encode($imageData);
                                                                $dataUri = 'data:' . $imageMimeType . ';base64,' . $base64Image;
                                                                ?>
                                                                <img src="<?php echo $dataUri; ?>" alt="<?php echo $product['name']; ?>" width="100">
                                                            </td>
                                                            <td><?php echo $product['name']; ?></td>
                                                            <td>
                                                            <?php
                                                                
                                                                $descriptionSnippet = substr($product['description'], 0, 20);
                                                                echo $descriptionSnippet;
                                                                if (strlen($product['description']) > 20) {
                                                                    echo '...'; 
                                                                }
                                                            ?>
                                                            </td>
                                                            <td>$<?php echo $product['price']; ?></td>
                                                            <td><?php echo $product['category']; ?></td>
                                                            <td>
                                                            <a href="?edit=true&productId=<?php echo $product['_id']; ?>" class="btn btn-primary edit-product-button" style="background-color: black; color: white; border-radius: 10px; padding: 5px 15px; text-decoration: none;">Edit</a>
                                                                <form method="POST" action="adminProducts.php" style="display: inline;">
                                                                    <input type="hidden" name="deleteProduct" value="true">
                                                                    <input type="hidden" name="productId" value="<?php echo $product['_id']; ?>">
                                                                    <button type="submit" class="btn btn-danger" style="background-color: red; color: white; border-radius: 10px; padding: 5px 15px; text-decoration: none;" onclick="return confirm('Are you sure you want to delete this product?')">Delete</button>
                                                                </form>
                                                            </td>
                                                        </tr>
                                                    <?php } ?>
                                                </tbody>
                                            </table>
                                        <?php } ?>
                                        <button id="addProductButton" class="btn btn-primary" style="background-color: black; color: white; border-radius: 10px; padding: 5px 15px; text-decoration: none;">Add Product</button>
                                    </div>

                                    <!-- Add Product Form - Initially Hidden -->
                                    <div id="addProductForm" style="display: none;">
                                        <form method="POST" action="adminProducts.php" enctype="multipart/form-data">
                                            <input type="hidden" name="addProduct" value="true">
                                            <input type="hidden" id="productId" name="productId" value="">
                                            <div class="form-group">
                                                <label for="productImage">Product Image:</label>
                                                <input type="file" id="productImage" name="productImage" accept="image/*" class="form-control-file" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="productName">Product Name:</label>
                                                <input type="text" id="productName" name="productName" class="form-control" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="productDescription">Product Description:</label>
                                                <textarea id="productDescription" name="productDescription" class="form-control" required style="height:100px;"></textarea>
                                            </div>
                                            <div class="form-group">
                                                <label for="productPrice">Product Price:</label>
                                                <input type="number" id="productPrice" name="productPrice" class="form-control" required>
                                            </div>
                                            <div class="form-group">
                                            <label for="productCategory">Product Category:</label>
                                                <select id="productCategory" name="productCategory" class="form-control" required>
                                                    <option value="Coffee">Coffee</option>
                                                    <option value="Accessories">Accessories</option>
                                                </select>
                                            </div>
                                            <button type="submit" class="btn btn-primary" style="background-color: black; color: white; border-radius: 10px; padding: 5px 15px; text-decoration: none;">Add Product</button>
                                            <button id="cancelButton" class="btn btn-danger" style="background-color: red; color: white; border-radius: 10px; padding: 5px 15px; text-decoration: none; display: none;">Cancel</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
    document.getElementById('addProductButton').addEventListener('click', function () {
        var productList = document.getElementById('productList');
        var addProductForm = document.getElementById('addProductForm');
        var addProductButton = document.getElementById('addProductButton');
        var cancelButton = document.getElementById('cancelButton');

        if (productList.style.display !== 'none') {
            productList.style.display = 'none';
            addProductForm.style.display = 'block';
            addProductButton.style.display = 'none'; 
            cancelButton.style.display = 'inline'; 
        } else {
            productList.style.display = 'block';
            addProductForm.style.display = 'none';
            addProductButton.style.display = 'inline'; 
            cancelButton.style.display = 'none';
        }
    });

    document.getElementById('cancelButton').addEventListener('click', function () {
        var productList = document.getElementById('productList');
        var addProductForm = document.getElementById('addProductForm');
        var addProductButton = document.getElementById('addProductButton');
        var cancelButton = document.getElementById('cancelButton');

        productList.style.display = 'block'; 
        addProductForm.style.display = 'none'; 
        addProductButton.style.display = 'inline'; 
        cancelButton.style.display = 'none'; 
    });

   
const urlParams = new URLSearchParams(window.location.search);
const isEditModeEnabled = urlParams.get('edit') === 'true';
const productId = urlParams.get('productId');


    
    </script>

<body>
</html>