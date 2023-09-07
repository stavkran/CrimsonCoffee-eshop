<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['loggedInUser']) && $_SESSION['loggedInUser'] === 'admin@email.com') {
    include('include/adminHeader.php');
} else {
    include('include/header.php');
}


if (isset($_SESSION['loggedInUser'])) {

    $loggedInUsername = $_SESSION['loggedInUser'];

    $isAdmin = ($loggedInUsername === 'admin@email.com');
    echo '<p class="text-success text-center" style="padding-top: 50px;">Hey, ' . $loggedInUsername . '. You are already Logged In.</p>';
    if (!$isAdmin) {
        echo '<p class="text-center style="padding-top: 20px;"><a href="homepage.php" style="background-color: black; color: white; border-radius: 10px; padding: 5px 15px; text-decoration: none;">Go to Homepage</a></p>';
        echo '<p class="text-center style="padding-top: 20px;"><a href="userProfile.php" style="background-color: black; color: white; border-radius: 10px; padding: 5px 15px; text-decoration: none;">Go to your Profile</a></p>';
        echo '<p class="text-center style="padding-top: 20px;"><a href="wishlistPage.php" style="background-color: black; color: white; border-radius: 10px; padding: 5px 15px; text-decoration: none;">See Your Wishlist</a></p>';
        echo '<p class="text-center style="padding-top: 20px;"><a href="purchaseHistory.php" style="background-color: black; color: white; border-radius: 10px; padding: 5px 15px; text-decoration: none;">See Your Purchase History</a></p>';
    } else {
        echo '<p class="text-center style="padding-top: 20px;"><a href="adminHomepage.php" style="background-color: black; color: white; border-radius: 10px; padding: 5px 15px; text-decoration: none;">Go to Homepage</a></p>';
    }

    echo '<div class="footer" style="padding: 20px 0; position: absolute; bottom: 0; width: 100%; z-index: 2;">';
    include('include/footer.html');
    echo '</div>';
    exit();
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST['email']);
    $password = $_POST['password'];

    require_once __DIR__ . '/vendor/autoload.php';

    $mongoClient = new MongoDB\Client("mongodb://localhost:27017");
    $db = $mongoClient->crimsonCoffee;
    $collection = $db->users;

    $user = $collection->findOne(['email' => $email]);

    if ($email === 'admin@email.com' && $password === 'admin') {
        $_SESSION['loggedInUser'] = $email;
        ob_start(); 
        echo '<script>window.location.href = "adminHomepage.php";</script>';
        ob_end_flush(); 
        exit();
    }elseif ($user && $user['password'] === $password) {
        // Successful login
        date_default_timezone_set('Europe/Athens');
        $_SESSION['loggedInUser'] = $email;
        $_SESSION['loginDate'] = date("Y-m-d"); 
        $_SESSION['loginTime'] = date("H:i:s");

        //********** Login History **********//
        recordLoginHistory($email, date("Y-m-d"), date("H:i:s")); 

        ob_start(); 
        echo '<script>window.location.href = "signIn.php";</script>';
        ob_end_flush(); 
        exit();
    } else {
        $loginError = true;
    }
}

function recordLoginHistory($email, $logindate, $logintime) {
    try {
        $mongoClient = new MongoDB\Client("mongodb://localhost:27017");
        $db = $mongoClient->crimsonCoffee;
        $loginHistoryCollection = $db->loginHistory;

        $loginHistoryDocument = [
            'email' => $email,
            'logindate' => $logindate,
            'logintime' => $logintime,
        ];

        $loginHistoryCollection->insertOne($loginHistoryDocument);
    } catch (MongoDB\Driver\Exception\Exception $e) {
        echo 'Error recording login history: ' . $e->getMessage();
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crimson Coffee | SignIn</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta2/css/all.min.css" integrity="sha512-YWzhKL2whUzgiheMoBFwW8CKV4qpHQAEuvilg9FAn5VJUDwKZZxkJNuGM4XkWuk94WCrrwslk8yWNGmY1EduTA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro&display=swap" rel="stylesheet">

    <link type="text/css" rel="stylesheet" href="/CrimsonCoffeeEshop/css/header.css">
    <link type="text/css" rel="stylesheet" href="/CrimsonCoffeeEshop/css/styles.css">
    <link type="text/css" rel="stylesheet" href="/CrimsonCoffeeEshop/css/signIn.css">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
<body>
    <section class="vh-40">
        <div class="container h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col-lg-12 col-xl-11">
                    <div class="card text-black" style="border-radius: 25px;">
                        <div class="card-body p-md-5">
                            <div class="row justify-content-center">
                                <div class="col-md-10 col-lg-6 col-xl-5 order-2 order-lg-1">
                                    <p class="text-center h1 fw-bold mb-5 mx-1 mx-md-4 mt-4">Sign In</p>
                                    <form class="mx-1 mx-md-4" method="POST" action="">
                                        <div class="d-flex flex-row align-items-center mb-4">
                                            <i class="fas fa-envelope fa-lg me-3 fa-fw"></i>
                                            <div class="form-outline flex-fill mb-0">
                                                <input type="email" name="email" id="form3Example3c" class="form-control" required>
                                                <label class="form-label" for="form3Example3c">Email</label>
                                            </div>
                                        </div>
                                        <div class="d-flex flex-row align-items-center mb-4">
                                            <i class="fas fa-lock fa-lg me-3 fa-fw"></i>
                                            <div class="form-outline flex-fill mb-0">
                                                <input type="password" name="password" id="form3Example4c" class="form-control" required>
                                                <label class="form-label" for="form3Example4c">Password</label>
                                            </div>
                                        </div>
                                        <div class="form-check d-flex justify-content-center mb-5">
                                            <input class="form-check-input me-2" type="checkbox" value="" id="form2Example3c">
                                            <label class="form-check-label" for="form2Example3c">
                                                I agree all statements in <a class="terms" href="#!">Terms of service</a>
                                            </label>
                                        </div>
                                        <div class="d-flex justify-content-center mx-4 mb-3 mb-lg-4">
                                            <button type="submit" class="btn btn-primary btn-lg" id="btnacc">Login</button>
                                        </div>
                                    </form>
                                    <div class="d-flex justify-content-center mx-4 mb-3 mb-lg-4">
                                        <p class="text-center">Don't have an account? <a href="signUp.php" class="terms">Sign Up</a></p>
                                    </div>
                                    <?php
                                    if (isset($loginError) && $loginError === true) {
                                        echo '<p class="text-danger text-center">Incorrect email or password. Please try again.</p>';
                                    }
                                    ?>
                                </div>
                                <div class="col-md-10 col-lg-6 col-xl-7 d-flex align-items-center order-1 order-lg-2">
                                    <img src="./images/dinosaur.png" class="img-fluid" alt="Sample image">
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