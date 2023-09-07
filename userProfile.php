<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include('include/header.php');


require_once __DIR__ . '/vendor/autoload.php';

$mongoClient = new MongoDB\Client("mongodb://localhost:27017");
$db = $mongoClient->crimsonCoffee;
$collection = $db->users;

if (!isset($_SESSION['loggedInUser'])) {
    header('Location: signIn.php');
    exit();
}

$loggedInEmail = $_SESSION['loggedInUser'];

$filter = ['email' => $loggedInEmail];
$userData = $collection->findOne($filter);

if (!$userData) {
    header('Location: signIn.php');
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $newFirstname = $_POST['firstname'];
    $newLastname = $_POST['lastname'];
    $newAddress = $_POST['address'];
    $newTelephone = $_POST['telephone'];

    $updateResult = $collection->updateOne(
        ['email' => $loggedInEmail],
        ['$set' => [
            'firstname' => $newFirstname,
            'lastname' => $newLastname,
            'address' => $newAddress,
            'telephone' => $newTelephone,
        ]]
    );

    if ($updateResult->getModifiedCount() > 0) {
        $userData = $collection->findOne($filter);
    }
}

function isEditModeEnabled() {
    return isset($_GET['edit']) && $_GET['edit'] === 'true';
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta2/css/all.min.css" integrity="sha512-YWzhKL2whUzgiheMoBFwW8CKV4qpHQAEuvilg9FAn5VJUDwKZZxkJNuGM4XkWuk94WCrrwslk8yWNGmY1EduTA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro&display=swap" rel="stylesheet">

    <link type="text/css" rel="stylesheet" href="/CrimsonCoffeeEshop/css/header.css">
    <link type="text/css" rel="stylesheet" href="/CrimsonCoffeeEshop/css/signIn.css">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
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
                                    <h1 class="text-center fw-bold mb-5">User Profile</h1>

                                    <p class="text-center" style="padding-top: 20px;"><a href="wishlistPage.php" style="background-color: black; color: white; border-radius: 10px; padding: 5px 15px; text-decoration: none;">See Your Wishlist</a></p>
                                    <p class="text-center" style="padding-top: 20px;"><a href="purchaseHistory.php" style="background-color: black; color: white; border-radius: 10px; padding: 5px 15px; text-decoration: none;">See Your Purchase History</a></p>

                                    <?php if (isEditModeEnabled()) { ?>
                                        <form method="POST" action="userProfile.php">
                                            <div class="form-group">
                                                <label for="firstname">First Name:</label>
                                                <input type="text" id="firstname" name="firstname" class="form-control" value="<?php echo $userData['firstname']; ?>" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="lastname">Last Name:</label>
                                                <input type="text" id="lastname" name="lastname" class="form-control" value="<?php echo $userData['lastname']; ?>" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="address">Address:</label>
                                                <input type="text" id="address" name="address" class="form-control" value="<?php echo $userData['address']; ?>" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="telephone">Telephone:</label>
                                                <input type="text" id="telephone" name="telephone" class="form-control" value="<?php echo $userData['telephone']; ?>" required>
                                            </div>
                                            <button type="submit" class="btn btn-primary" style="background-color: black; color: white; border-radius: 10px; padding: 5px 15px; text-decoration: none;">Save Changes</button>
                                            <a href="userProfile.php" class="btn btn-secondary" style="background-color: red; color: white; border-radius: 10px; padding: 5px 15px; text-decoration: none;">Cancel</a>
                                        </form>
                                    <?php } else { ?>
                                        <div class="mb-4">
                                            <p><strong>First Name:</strong> <?php echo $userData['firstname']; ?></p>
                                            <p><strong>Last Name:</strong> <?php echo $userData['lastname']; ?></p>
                                            <p><strong>Address:</strong> <?php echo $userData['address']; ?></p>
                                            <p><strong>Telephone:</strong> <?php echo $userData['telephone']; ?></p>
                                        </div>
                                        <a href="?edit=true" class="btn btn-primary" style="background-color: black; color: white; border-radius: 10px; padding: 5px 15px; text-decoration: none;">Edit</a>
                                    <?php } ?>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="footer" style=" padding: 20px 0;position: absolute;bottom: -50;width: 100%;z-index: 2;">
        <?php
        include('include/footer.html');
        ?>
    </div> 

</body>
</html>