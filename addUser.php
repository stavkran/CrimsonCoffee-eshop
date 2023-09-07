<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include('include/adminHeader.php');

require_once __DIR__ . '/vendor/autoload.php';

$message = ''; 
$messageColor = '';


$mongoClient = new MongoDB\Client("mongodb://localhost:27017");
$db = $mongoClient->crimsonCoffee;
$collection = $db->users;


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $address = $_POST['address'];
    $telephone = $_POST['telephone'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password']; 


    $insertResult = $collection->insertOne([
        'firstname' => $firstname,
        'lastname' => $lastname,
        'address' => $address,
        'telephone' => $telephone,
        'email' => $email,
        'username' => $username,
        'password' => $password 
    ]);

    if ($insertResult->getInsertedCount() === 1) {
        $message = "User added successfully.";
        $messageColor = 'text-success'; 
    } else {
        $message = "Error adding user.";
        $messageColor = 'text-danger'; 
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New User</title>
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
                                    <h1 class="text-center fw-bold mb-5">Add New User</h1>

                                    <div class="text-center <?= $messageColor; ?> mb-3"><?= $message; ?></div>

                    
                                    <form method="post">
                                        <label for="firstname">First Name:</label>
                                        <input type="text" name="firstname" required><br>

                                        <label for="lastname">Last Name:</label>
                                        <input type="text" name="lastname" required><br>

                                        <label for="address">Address:</label>
                                        <input type="text" name="address" required><br>

                                        <label for="telephone">Telephone:</label>
                                        <input type="text" name="telephone" required><br>

                                        <label for="email">Email:</label>
                                        <input type="email" name="email" required><br>

                                        <label for="username">Username:</label>
                                        <input type="text" name="username" required><br>

                                        <label for="password">Password:</label>
                                        <input type="password" name="password" required><br>

                                        <button type="submit" class="btn btn-success">Add User</button>
                                    </form>
                                    <a href="adminUsersEdit.php" class="btn btn-primary" style="background-color: black; color: white; padding:5px; border-radius: 5px; border-color: black; margin-top:10px;">Back to User List</a>
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
