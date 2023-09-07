<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include('include/adminHeader.php');

require_once __DIR__ . '/vendor/autoload.php';

$mongoClient = new MongoDB\Client("mongodb://localhost:27017");
$db = $mongoClient->crimsonCoffee;
$collection = $db->users;


if (isset($_GET['id'])) {
    $userID = $_GET['id'];


    $user = $collection->findOne(['_id' => new MongoDB\BSON\ObjectId($userID)]);

    if ($user) {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $newFirstName = $_POST['new_firstname'];
            $newLastName = $_POST['new_lastname'];
            $newAddress = $_POST['new_address'];
            $newTelephone = $_POST['new_telephone'];
            $newEmail = $_POST['new_email'];


            $updateResult = $collection->updateOne(
                ['_id' => new MongoDB\BSON\ObjectId($userID)],
                [
                    '$set' => [
                        'firstname' => $newFirstName,
                        'lastname' => $newLastName,
                        'address' => $newAddress,
                        'telephone' => $newTelephone,
                        'email' => $newEmail
                    ]
                ]
            );

        
        }
        ?>

<!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>User Profiles</title>
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
            <div class="user-edit-container">
            <div class="message-container">
                <h2 class="user-edit-title">Edit User</h2>
            
                <div class="message-container">
                    <?php
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        if ($updateResult->getModifiedCount() === 1) {
                            echo '<div class="success-message">User data updated successfully.</div>';

        
                            $user = $collection->findOne(['_id' => new MongoDB\BSON\ObjectId($userID)]);
                        } else {
                            echo '<div class="error-message">Error updating user data.</div>';
                        }
                    }
                    ?>
                </div>
                <form class="user-edit-form" method="post">
                    <input type="hidden" name="user_id" value="<?= $userID ?>">
                    <label for="new_firstname">First Name:</label>
                    <input type="text" name="new_firstname" value="<?= $user['firstname'] ?>"><br>

                    <label for="new_lastname">Last Name:</label>
                    <input type="text" name="new_lastname" value="<?= $user['lastname'] ?>"><br>

                    <label for="new_address">Address:</label>
                    <input type="text" name="new_address" value="<?= $user['address'] ?>"><br>

                    <label for="new_telephone">Telephone:</label>
                    <input type="text" name="new_telephone" value="<?= $user['telephone'] ?>"><br>

                    <label for="new_email">Email:</label>
                    <input style="background-color:#fcd4da;" type="text" name="new_email" value="<?= $user['email'] ?>" readonly><br>

                    <label for="password">Password:</label>
                    <input style="background-color:#fcd4da;" type="password" name="password" value="<?= maskPassword($user['password']) ?>" readonly><br>

                    <div class="button-group">
                        <button type="submit">Update</button>
                        <a href="adminUsersEdit.php" class="back-to-list-link" style="background-color: #686969; color: white; border-radius: 10px; border-color: #686969;">Back to User List</a>
                    </div>
                </form>

            </div>
        </body>
        </html>

        <?php
    } else {
        echo "User not found.";
    }
} else {
    echo "User ID not provided.";
}

function maskPassword($password) {
    // Replace each character with an asterisk (*) to mask it
    return str_repeat('*', strlen($password));
}

?>
