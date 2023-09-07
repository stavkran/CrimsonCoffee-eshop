<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crimson Coffee | SignUp</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta2/css/all.min.css" integrity="sha512-YWzhKL2whUzgiheMoBFwW8CKV4qpHQAEuvilg9FAn5VJUDwKZZxkJNuGM4XkWuk94WCrrwslk8yWNGmY1EduTA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro&display=swap" rel="stylesheet">

    <link type="text/css" rel="stylesheet" href="/CrimsonCoffeeEshop/css/header.css">
    <!--<link type="text/css" rel="stylesheet" href="/CrimsonCoffeeEshop/css/footer.css"> -->

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

</head>
<body>
    <?php
        include('include/header.php');
        require_once('signUpDB.php'); 

        $message = '';

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $firstname = filter_input(INPUT_POST, 'firstname', FILTER_SANITIZE_STRING);
            $lastname = filter_input(INPUT_POST, 'lastname', FILTER_SANITIZE_STRING);
            $address = filter_input(INPUT_POST, 'address', FILTER_SANITIZE_STRING);
            $telephone = filter_input(INPUT_POST, 'telephone', FILTER_SANITIZE_STRING);
            $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL); 
            $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
            $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
            $confirm_password = filter_input(INPUT_POST, 'confirm_password', FILTER_SANITIZE_STRING);

            if (isValidInput($firstname, $lastname, $address, $telephone, $email, $username, $password, $confirm_password)) {
                if ($password === $confirm_password) {
                    if (insertUserData($firstname, $lastname, $address, $telephone, $email, $username, $password)) {
                        $message = 'Registration successful.';
                        echo '<script>window.location.href = "signIn.php";</script>';
                        exit();
                    } else {
                        $message = 'Registration failed.';
                        exit();
                    }
                } else {
                    $message = 'Invalid data.';
                    exit();
                }
            } else {
                header('Location: signup.php?signup=invalid');
                exit();
            }
        }
    ?>

<section class="vh-40">
        <div class="container h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
            <div class="col-lg-12 col-xl-11">
                <div class="card text-black" style="border-radius: 25px;">
                <div class="card-body p-md-5">
                    <div class="row justify-content-center">
                    <div class="col-md-10 col-lg-6 col-xl-5 order-2 order-lg-1">

                        <p class="text-center h1 fw-bold mb-5 mx-1 mx-md-4 mt-4">Sign up</p>
                        
                        <form method="post" action="signup.php" class="mx-1 mx-md-4">
                            
                        <div class="d-flex flex-row align-items-center mb-4">
                            <i class="fas fa-user fa-lg me-3 fa-fw"></i>
                            <div class="form-outline flex-fill mb-0">
                            <input type="text" id="form3Example1c" class="form-control" name="firstname" />
                            <label class="form-label" for="form3Example1c">Your Name</label>
                            </div>
                        </div>
                        
                        <div class="d-flex flex-row align-items-center mb-4">
                            <i class="fas fa-user fa-lg me-3 fa-fw"></i>
                            <div class="form-outline flex-fill mb-0">
                            <input type="text" id="form3Example2c" class="form-control" name="lastname" />
                            <label class="form-label" for="form3Example2c">Your Last Name</label>
                            </div>
                        </div>
                        
                        <div class="d-flex flex-row align-items-center mb-4">
                            <i class="fas fa-map-marker-alt fa-lg me-3 fa-fw"></i>
                            <div class="form-outline flex-fill mb-0">
                            <input type="text" id="form3Example3c" class="form-control" name="address" />
                            <label class="form-label" for="form3Example3c">Your Address</label>
                            </div>
                        </div>
                        
                        <div class="d-flex flex-row align-items-center mb-4">
                            <i class="fas fa-phone fa-lg me-3 fa-fw"></i>
                            <div class="form-outline flex-fill mb-0">
                            <input type="text" id="form3Example4c" class="form-control" name="telephone" />
                            <label class="form-label" for="form3Example4c">Your Phone Number</label>
                            </div>
                        </div>

                        <div class="d-flex flex-row align-items-center mb-4">
                            <i class="fas fa-envelope fa-lg me-3 fa-fw"></i>
                            <div class="form-outline flex-fill mb-0">
                            <input type="email" id="form3Example5c" class="form-control" name="email" />
                            <label class="form-label" for="form3Example5c">Your Email</label>
                            </div>
                        </div>
                        
                        <div class="d-flex flex-row align-items-center mb-4">
                            <i class="fas fa-user fa-lg me-3 fa-fw"></i>
                            <div class="form-outline flex-fill mb-0">
                            <input type="text" id="form3Example6c" class="form-control" name="username" />
                            <label class="form-label" for="form3Example6c">Choose a Username</label>
                            </div>
                        </div>
                        
                        <div class="d-flex flex-row align-items-center mb-4">
                            <i class="fas fa-lock fa-lg me-3 fa-fw"></i>
                            <div class="form-outline flex-fill mb-0">
                            <input type="password" id="form3Example7c" class="form-control" name="password" />
                            <label class="form-label" for="form3Example7c">Password</label>
                            </div>
                        </div>
                        
                        <div class="d-flex flex-row align-items-center mb-4">
                            <i class="fas fa-key fa-lg me-3 fa-fw"></i>
                            <div class="form-outline flex-fill mb-0">
                            <input type="password" id="form3Example8cd" class="form-control" name="confirm_password" />
                            <label class="form-label" for="form3Example8cd">Repeat your password</label>
                            </div>
                        </div>
                        
                        <div class="form-check d-flex justify-content-center mb-5">
                            <input class="form-check-input me-2" type="checkbox" value="" id="form2Example9c" />
                            <label class="form-check-label" for="form2Example9c">
                            I agree to all statements in <a class="terms" href="#!">Terms of service</a>
                            </label>
                        </div>
                        
                        <div class="d-flex justify-content-center mx-4 mb-3 mb-lg-4">
                            <button type="submit" class="btn btn-primary btn-lg" id="btnacc">Register</button>
                        </div>
                        
                        </form>
                        <?php if (!empty($message)): ?>
                            <p><?php echo $message; ?></p>
                        <?php endif; ?>
                        
                    </div>
                    <div class="col-md-10 col-lg-6 col-xl-7 d-flex align-items-center order-1 order-lg-2">
                        
                        <img src="/CrimsonCoffeeEshop/images/dinosaur.png"
                        class="img-fluid" alt="Sample image">
                        
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