<?php
include('include/header.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crimson Coffee | About</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta2/css/all.min.css" integrity="sha512-YWzhKL2whUzgiheMoBFwW8CKV4qpHQAEuvilg9FAn5VJUDwKZZxkJNuGM4XkWuk94WCrrwslk8yWNGmY1EduTA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro&display=swap" rel="stylesheet">

    <link type="text/css" rel="stylesheet" href="/CrimsonCoffeeEshop/css/header.css">
    <link type="text/css" rel="stylesheet" href="/CrimsonCoffeeEshop/css/about.css">
    <link type="text/css" rel="stylesheet" href="/CrimsonCoffeeEshop/css/footer.css">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
<body>
    <div id="section-page-about-image-collage" class="section">
        <section id="imageCollage" class="imageCollage section section-rounded section-roundedTop">
            <div class="section_inner">
                <div class="container">
                    <div class="imageCollage_row">
                        <div class="imageCollage_column">

                            <!-- <div class="imageCollage_image-mobile">
                                <img class="lazy" data-src="//cdn.shopify.com/s/files/1/0485/4707/0108/files/image-collage-phones-mobile-Recovered.jpg?v=1635977221">
                            </div> -->
                            
                            <div class="image_collages">
                                <div class="image_collage" style="top: -25.6185px;">
                                    <img class="lazy lazy-loaded" alt="" src="./images/Artboard – 1.png" data-was-processed="true">
                                </div>
                                <div class="image_collage" style="top: 25.6185px;">
                                    <img class="lazy lazy-loaded" alt="" src="./images/Artboard – 2.png" data-was-processed="true">
                                </div>
                            </div>
                        </div>
                        
                        <div class="imageCollage_column">
                            <div class="imageCollage_textContainer">
                                <h1 class="imageCollage_title">Hi, We're Crimson.<br>
                                This is our coffee.</h1>
                                
                                <div class="imageCollage_text">
                                    <div style="text-align: left;">Lorem, ipsum dolor sit amet consectetur adipisicing elit. Consectetur voluptatem beatae vel molestiae rerum! Modi voluptate quos voluptatibus praesentium et amet! Perspiciatis commodi cumque sed molestias adipisci dignissimos illo, maxime dolore? Magnam necessitatibus perferendis eius omnis. Quod sequi soluta consequuntur.
                                    </div>
                                    <br>
                                    <div style="text-align: left;">Lorem ipsum dolor sit amet consectetur adipisicing elit. Alias nobis in consequatur. Eveniet eius cum, optio est voluptatum praesentium exercitationem quaerat magni quibusdam iste vel iusto necessitatibus, ipsam quam ab ratione iure alias eum assumenda dolores facere ipsa quae sunt. Quod porro dolore, delectus recusandae earum esse nihil repellendus impedit vero voluptates sed aliquam, ab consequatur molestias quos qui soluta eaque, laborum dolorum aperiam eligendi! Consectetur similique reprehenderit provident earum!
                                    </div>
                                    <br>
                                    <div style="text-align: left;">Lorem ipsum dolor sit amet consectetur adipisicing elit. Placeat fugit at ipsam eveniet nihil tempore!</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <div class="footer">
        <?php
        include('include/footer.html');
        ?>
        </div>
        
        
        <script>
            var windowHeight = window.innerHeight;
            var windowWidth = window.innerWidth;
            var imageCollage = document.getElementById('imageCollage');
            var imageCollageHeight = imageCollage.offsetHeight;
            var scrollArea = imageCollageHeight;
            var collageImage1 = document.getElementsByClassName('image_collage')[0];
            var collageImage2 = document.getElementsByClassName('image_collage')[1];
            
            window.addEventListener('scroll', function() {
                var scrollTop = window.pageYOffset || window.scrollTop;
                var scrollPercent = scrollTop/scrollArea || 0;
                collageImage1.style.top = -scrollPercent*window.innerWidth*0.05 + 'px';
                collageImage2.style.top = scrollPercent*window.innerWidth*0.05 + 'px';
        });
        </script>
        
        
        </div>
</body>
</html>


