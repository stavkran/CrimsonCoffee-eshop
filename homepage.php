<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crimson Coffee</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta2/css/all.min.css" integrity="sha512-YWzhKL2whUzgiheMoBFwW8CKV4qpHQAEuvilg9FAn5VJUDwKZZxkJNuGM4XkWuk94WCrrwslk8yWNGmY1EduTA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro&display=swap" rel="stylesheet">

    <link type="text/css" rel="stylesheet" href="/CrimsonCoffeeEshop/css/styles.css">
    <link type="text/css" rel="stylesheet" href="/CrimsonCoffeeEshop/css/hp_section1.css">
    <link type="text/css" rel="stylesheet" href="/CrimsonCoffeeEshop/css/hp_section2.css">\
    <link type="text/css" rel="stylesheet" href="/CrimsonCoffeeEshop/css/hp_sections3_4.css">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
<body>
    <?php
    include('include/header.php');
    ?>

    <div class="content" style="margin-bottom:10px">
        <div class="mw-100" style="height:400px; background-image: url('/CrimsonCoffeeEshop/images/basquiatPainting.png'); background-size: cover; opacity: 90%;">
            <div class="imgtext">
                <h1 class="titlesec1">MORE THAN COFFEE</h1>
                <h3 class="descrsec1" id="descriptionsec1">Shop our high-quality coffee</h3>
                <h3 class="descrsec1">Free shipping on all EU orders over 60 EUR</h3>
                <a href="/CrimsonCoffeeEshop/productsPage.php" class="btn btn-outline-black btn-lg active" role="button" aria-pressed="true" id="shopNowbtn">Shop Now</a>
            </div>
        </div>
    </div>

    <div style="height: 100px; background-color: rgba(255, 255, 255, 0.1); box-shadow: rgba(149, 157, 165, 0.1) 0px 8px 24px;">
        
        <div class="d-flex justify-content-center">

            <nav class="navbar navbar-expand-lg bg-white sticky-top navbar-light p-4">
                <div class="lefttext">
                    <p>Ethically Made</p>
                </div>

                <div class="righttext">
                    <div>
                        <img class="starimg" src="/CrimsonCoffeeEshop/images/star-vector.png" >
                        <img class="starimg" src="/CrimsonCoffeeEshop/images/star-vector.png" >
                        <img class="starimg" src="/CrimsonCoffeeEshop/images/star-vector.png" >
                        <img class="starimg" src="/CrimsonCoffeeEshop/images/star-vector.png" >
                        <img class="starimg" src="/CrimsonCoffeeEshop/images/star-vector.png" >
                        <p>12,0000+ reviews</p>
                    </div>
                </div>
            </nav>

        </div>
    </div>

    <?php
        
        require __DIR__ . '/vendor/autoload.php';
        $mongoClient = new MongoDB\Client("mongodb://localhost:27017");
        $db = $mongoClient->crimsonCoffee;
        $collection = $db->products;


        $products = $collection->find();

        echo '<div class="mw-100" style="height:800px; background-color: #F2EEEE; background-size: cover;">';
        echo '<h1 class="titlesec2">Get started with Crimson.</h1>';
        echo '<h3 class="textsec2">Get started with one of our employee favourites including everything you need for the perfect coffee or accessories!</h3>';
        echo '<div class="d-flex justify-content-center" id="productscard">';

        $count = 0;

        foreach ($products as $document) {
            if ($count >= 3) {
                break;
            }

            echo '<div class="card mx-2" style="width: 18rem;">';
            $imageData = $document['image']->getData();
            $imageMimeType = $document['image']->getType();
            $base64Image = base64_encode($imageData);
            $dataUri = 'data:' . $imageMimeType . ';base64,' . $base64Image;

            echo '<img src="' . $dataUri . '" class="card-img-top">';
            echo '<div class="card-body">';
            echo '<h5 class="card-title">' . $document["name"] . '</h5>';
            echo '</div>';
            echo '</div>';

            $count++;
        }

                echo '</div>';
                echo '</div>';
            ?>
        <center>
        <div class="mw-100" style="height:500px; background-color: #F2EEEE; background-size: cover; margin-top: 150px;">
            
            <h1 class="titlesec3" style="margin-bottom:5px;">Check out our new products.</h1>

            <h2 class="subscrsec">
                
                You can now shop via our social media! Links are available in the footer... Join us!
            
            </h2>
                
        </div>
        </center>

    <div class="mw-100" style="height:900px; background-color: #F2EEEE; background-size: cover; margin-top: 150px;">
    
        <h1 class="titlesec4">Say hello to Crimson Coffee.<br>A new kinda coffee.</h1>

        <div class="text-center">
            <img src="images/coffee.jpg" class="imgsec4">
        </div>

        <h3 class="smalltitlesec4">Hi, we are Crimson. This is our coffee.</h3>

        <h4 class="textsec4">Coffee. For some people is more than a drink.<br> 
            It’s a way to connect. It’s a way to share moments.<br> 
            And, ok, sometimes it’s just a way to wake up and get shit done.</h4>

        <a class="linksec4" href="/CrimsonCoffeeEshop/about.php"><p class="textlinksec4">Read the story ></p></a>

    </div>

    <div class="mw-100" style="height:1500px; background-color: #F2EEEE; background-size: cover; margin-top: 150px;">
        
        <h1 class="titlesec5">You are what you drink</h1>

        <h4 class="textsec5">Here’s how we make sure every cup of coffee you<br> 
            enjoy from us has been sourced, made and delivered<br> 
            to you in the most sustainable way possible.</h4>

        <div class="d-flex justify-content-center" id="productscard">
        <div class="card mx-5" style="width: 20rem; background-color: #F2EEEE;">
            <img src="images/box.png" class="card-img-top" style="width: 15rem;">
            <div class="card-body">
            <h5 class="card-title">Responsibly made</h5>
            <p class="card-text">All our products are freshly roasted and prepared at local farms, ensuring the best possible standards and labor practices.</p>
            </div>
        </div>

        <div class="card mx-5" style="width: 20rem; background-color: #F2EEEE;">
            <img src="images/recycle.png" class="card-img-top" style="width: 15rem;">
            <div class="card-body">
            <h5 class="card-title">sustainably packaged</h5>
            <p class="card-text">We try to include as many environmentally friendly materials as possible without degrading the coffee.</p>
            </div>
        </div>
        </div>

        <center>
        <div class="d-flex justify-content-center" id="signupbox">
            
            <div class="block1 mx-5">
                <img src="images/dinosaur.png" style="width: 300px;">
            </div>

            <div class="block2 mx-5">
                <h1 class="titlesec5-1">Got FOMO?</h1>

                <h4 class="textsec5-1">Stay up to date with all things from Crimson Coffee,<br>and get the newsletter straight to your inbox... We send<br> secret news and recipes and you get 10% off your first order.</h4>


            <section class="home-newsletter">
                <div class="container">
                <div class="row">
                <div class="col-sm-12">
                    <div class="single">
                    <div class="input-group">
                        <input type="email" class="form-control" placeholder="Enter your email">
                        <span class="input-group-btn">
                        <button class="btn btn-theme" type="submit">Subscribe</button>
                        </span>
                        </div>
                    </div>
                </div>
                </div>
                </div>
                </section>
            </div>
            
        </div>
        </center>
    </div>



    <div class="footer" style=" padding: 20px 0;position: absolute;bottom: -50;width: 100%;z-index: 2;">
        <?php
        include('include/footer.html');
    ?>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>
</html>


