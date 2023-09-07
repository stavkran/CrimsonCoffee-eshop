<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
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

    <link type="text/css" rel="stylesheet" href="/CrimsonCoffeeEshop/css/header.css">
    <link type="text/css" rel="stylesheet" href="/CrimsonCoffeeEshop/css/adminHeader.css">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">


</head>
<body>
    <div id="menuHolder">
        <header class="sticky-top border-bottom border-top py-5" id="mainNavigation">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-4">
                        <span class="wave">👋🏿</span>
                            
                    </div>
                    <div class="col-md-4 text-center">
                        <a href="/CrimsonCoffeeEshop/adminHomepage.php" class="btn btn-brand" role="button" id="siteBrand">
                            Crimson Coffee
                        </a>
                    </div>
                    <div class="flex2 text-end d-none d-md-block">
                        <div class="d-flex text-end d-none d-md-flex flex-column align-items-center">
                        <div class="d-flex flex-column align-items-end mt-2">
                            <?php
                            if (isset($_SESSION['loggedInUser'])) {
                                // User is logged in
                                echo '<div class="d-flex align-items-center">';
                                echo '<div class="nav-link me-4" style="color: #28a745;">' . $_SESSION['loggedInUser'] . '</div>';
                                echo '<a class="nav-link me-4" href="/CrimsonCoffeeEshop/signIn.php"><i class="fa-solid fa-circle-user fa-2xl"></i></a>';
                                echo '</div>';
                                echo '<div class="d-flex align-items-center">';
                                echo '<a class="btn btn-logout" href="/CrimsonCoffeeEshop/logout.php" style="background-color: black; color: white; border-radius: 10px; padding: 5px 15px; text-decoration: none;">Logout</a>';
                                

                                echo '</div>';
                            }else {
                                // User is not logged in
                                //echo '<a class="nav-link me-4" href="/CrimsonCoffeeEshop/signIn.php"><i class="fa-solid fa-circle-user fa-2xl"></i></a>';
                                echo '<div class="d-flex align-items-center">';
                                echo '<a class="nav-link me-4" href="/CrimsonCoffeeEshop/signIn.php"><i class="fa-solid fa-circle-user fa-2xl"></i></a>';
                                echo '<a class="nav-link" href="#"><i class="fa-solid fa-cart-shopping fa-2xl"></i></a>';
                                echo '</div>';
                            }
                            ?>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>
    </div>

    <div class="d-flex justify-content-around">
        <nav class="navbar navbar-expand-lg bg-white sticky-top navbar-light p-4">
        
            <div class=" collapse navbar-collapse" >
                <ul class="navbar-nav ms-auto ">
                    <li class="nav-item">
                        <a class="menu-item mx-5" href="/CrimsonCoffeeEshop/adminUsersEdit.php">Users Edit</a>
                    </li>
                    <li class="nav-item">
                        <a class="menu-item mx-5" href="/CrimsonCoffeeEshop/adminProducts.php">Products Edit</a>
                    </li>
                    <li class="nav-item">
                        <a class="menu-item mx-5" href="#">Orders Edit</a>
                    </li>
                </ul>

            </div>
        </nav>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>



</body>
</html>