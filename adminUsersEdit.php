<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include('include/adminHeader.php');

require_once __DIR__ . '/vendor/autoload.php';


$mongoClient = new MongoDB\Client("mongodb://localhost:27017");
$db = $mongoClient->crimsonCoffee;
$collection = $db->users;


$message = '';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['edit_user'])) {
        
        echo '<script>window.location.href = "editUser.php?id=' . $_POST['edit_user'] . '";</script>';
        exit();
    } elseif (isset($_POST['delete_user'])) {
       
        $deleteResult = $collection->deleteOne(['_id' => new MongoDB\BSON\ObjectId($_POST['delete_user'])]);
        if ($deleteResult->getDeletedCount() === 1) {
           
            $message = 'User deleted successfully.';
        } else {
            
            $message = 'Error deleting user.';
        }
    }
}

$totalUsers = $collection->countDocuments();

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
                                    <h1 class="text-center fw-bold mb-5" style="padding-bottom:5px">User Profiles</h1>
                                    
                                    <?php
                             
                                    if (!empty($message)) {
                                        echo '<div class="alert alert-info">' . $message . '</div>';
                                    }

                                 
                                    echo '<center><p style="margin-bottom: 10px; font-size:1.8em">Total Number of Users: ' . $totalUsers . '</p></center>';
                                    
                                    echo '<center><a href="addUser.php" class="btn btn-success" style="padding-left:20px; padding-right:20px; background-color: black; color: white; border-radius: 10px; border-color: black;">Add User</a></center>';
                                    echo '<hr>';
                                    
                                    $cursor = $collection->find([]);
                                    foreach ($cursor as $user) {
                                        echo '<div class="mb-4">';
                                        echo '<p><strong>User ID:</strong> ' . $user['_id'] . '</p>';
                                        echo '<p><strong>First Name:</strong> ' . $user['firstname'] . '</p>';
                                        echo '<p><strong>Last Name:</strong> ' . $user['lastname'] . '</p>';
                                        echo '<p><strong>Address:</strong> ' . $user['address'] . '</p>';
                                        echo '<p><strong>Telephone:</strong> ' . $user['telephone'] . '</p>';
                                        echo '<p><strong>Email:</strong> ' . $user['email'] . '</p>';
                                        echo '<p><strong>Username:</strong> ' . $user['username'] . '</p>';
                                        
                                        echo '<form method="post" style="display: inline-block; margin-right: 10px;">
                                                <input type="hidden" name="edit_user" value="' . $user['_id'] . '">
                                                <button type="submit" style="background-color: black; color: white; padding:5px; border-radius: 5px; border-color: black;">Edit</button>
                                            </form>';

                                        echo '<form method="post" style="display: inline-block;">
                                                <input type="hidden" name="delete_user" value="' . $user['_id'] . '" >
                                                <button type="submit" class="btn btn-danger">Delete</button>
                                            </form>';

                                        echo '<a href="userLoginHistory.php?id=' . $user['_id'] . '" class="btn btn-primary" style="padding-left:20px; padding-right:20px; background-color: black; color: white; border-radius: 10px; border-color: black; margin-left: 10px;">View Login History</a>';

                                        echo "<hr>";

                                    }
                                    ?>
                                    
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
