<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include('include/adminHeader.php');

require_once __DIR__ . '/vendor/autoload.php';


$mongoClient = new MongoDB\Client("mongodb://localhost:27017");
$db = $mongoClient->crimsonCoffee;
$usersCollection = $db->users;
$loginHistoryCollection = $db->loginHistory;


if (isset($_GET['id'])) {
    $userId = $_GET['id'];


    $user = $usersCollection->findOne(['_id' => new MongoDB\BSON\ObjectId($userId)]);

    if ($user) {
        $email = $user['email'];


        $userLoginHistoryCursor = $loginHistoryCollection->find(['email' => $email]);

        $userLoginHistory = iterator_to_array($userLoginHistoryCursor);


        echo '<!DOCTYPE html>
        <html lang="en">
        <head>
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
            <section class="vh-100" style="margin-top: -50px; margin-bottom: 25px;">
                <div class="container h-100">
                    <div class="row d-flex justify-content-center align-items-center h-100">
                        <div class="col-lg-12 col-xl-11">
                            <div class="card text-black" style="border-radius: 25px; margin-top: -20px;">
                                <div class="card-body p-md-5">
                                    <div class="row justify-content-center">
                                        <div class="col-md-10 col-lg-8 col-xl-7 order-2 order-lg-1">
                                            <center><h1 class="mb-5">User Login History for ' . $user['firstname'] . ' ' . $user['lastname'] . '</h1></center>';

                                    
                                            if (!empty($userLoginHistory)) {
                                                echo '<table class="table table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th>Login Date</th>
                                                                <th>Login Time</th>
                                                                <th>Session Duration (seconds)</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>';

                                                foreach ($userLoginHistory as $loginRecord) {
                                                    echo '<tr>
                                                            <td>' . $loginRecord['logindate'] . '</td>
                                                            <td>' . $loginRecord['logintime'] . '</td>
                                                            <td>' . $loginRecord['sessionduration'] . '</td>
                                                        </tr>';
                                                }

                                                echo '</tbody>
                                                    </table>';
                                            } else {
                                                echo '<center><p style="color:red;">No login history found for this user.</p></center>';
                                            }

                                            echo '<center><a href="adminUsersEdit.php" class="btn btn-secondary" style="background-color: black; padding-left:20px; padding-right:20px;">Back to User List</a></center>';

                                            echo '
                                            
                                </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        
            </body>
        </html>';
    } else {
        echo 'User not found.';
    }
} else {
    echo 'User ID not provided in the URL.';
}
?>
