<?php include("./header.php"); ?>
<!DOCTYPE html>
<html lang="en">
<!--divinectorweb.com-->
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <?php include("./assets/link.html"); ?>
    <link rel="stylesheet" href="./assets/css/home-style.css">
    <link rel="stylesheet" href="./assets/css/bootstrap.min.css">
</head>
<body>
    <!-- Navbar -->
    
    <?php include("navbar.php"); ?>
    
    <!-- heros section -->
    
    <?php include("heroes.php"); ?>
    
    <!-- About Section Starts -->
<section id="about" class="about section-padding">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-md-12 col-12">
                <div class="about-img">
                    <img style="overflow:hidden;" src="./images/heroes/1.jpg" alt="eAgriAuction service image" class="img-fluid">
                </div>
            </div>
            <div class="col-lg-8 col-md-12 col-12 ps-lg-5 mt-md-5">
                <div class="about-text">
                    <h2>Empowering Farmers with Better Returns <br/> Through eAuctions</h2>
                    <p>eAgriAuction is a dedicated platform designed to help farmers auction their agricultural produce for better returns. By leveraging technology, we bridge the gap between farmers and buyers, ensuring transparency and competitive pricing for products such as grains, vegetables, fruits, and more. Our platform enables farmers to take control of their sales process, minimize intermediaries, and maximize profits.</p>
                    <p>Whether you're a small-scale farmer or a large producer, eAgriAuction provides an easy-to-use interface to list your products, track bids in real-time, and secure payments seamlessly. Join us in revolutionizing agricultural trade and creating a brighter future for farmers across the nation.</p>
                </div>
            </div>
        </div>
    </div>
</section>
    <!-- About Section Ends -->

    <!-- Services Section Starts -->
    <section class="services" id="services">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="section-header text-center pb-5">
                        <h2>Our Services</h2>
                        <p>Empowering farmers and wholesalers with a seamless platform <br> to auction and bid for agricultural products.</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <!-- Service 1 -->
                <div class="col-12 col-md-12 col-lg-4">
                    <div class="card text-white text-center bg-dark pb-2">
                        <div class="card-body">
                            <i class="bi bi-basket"></i>
                            <h3 class="card-title">Auction Your Products</h3>
                            <p class="lead">Farmers can list their agricultural products like grains, vegetables, and fruits for auction directly through our platform.</p>
                    <!--        <button class="btn bg-success text-white">Learn More</button> -->
                        </div>
                    </div>
                </div>
                <!-- Service 2 -->
                <div class="col-12 col-md-12 col-lg-4">
                    <div class="card text-white text-center bg-dark pb-2">
                        <div class="card-body">
                            <i class="bi bi-currency-rupee"></i>
                            <h3 class="card-title">Competitive Bidding</h3>
                            <p class="lead">Wholesalers can bid competitively on agricultural products, ensuring fair prices for both farmers and buyers.</p>
                         <!--   <button class="btn bg-success text-white">Learn More</button> -->
                        </div>
                    </div>
                </div>
                <!-- Service 3 -->
                <div class="col-12 col-md-12 col-lg-4">
                    <div class="card text-white text-center bg-dark pb-2">
                        <div class="card-body">
                            <i class="bi bi-truck"></i>
                            <h3 class="card-title">Streamlined Logistics</h3>
                            <p class="lead">Our platform connects farmers with buyers and ensures smooth logistics for delivery of products.</p>
                           <!--  <button class="btn bg-success text-white">Learn More</button> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Services Section Ends -->

    <?php include("review.php"); ?>

    <!-- Contact Section Starts -->
    <section id="contact" class="contact">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="section-header text-center pb-2">
                        <h2>Contact Us</h2>
                        <p>Reach out to us for any inquiries or assistance.</p>
                    </div>
                </div>
            </div>
            <div class="row m-0">
                <div class="col-md-12 p-0 pt-2 pb-4">
                    <form id="contactForm" action="contact-mail.php" method="POST" class="bg-light p-4 m-0">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <input class="form-control" name="full_name" placeholder="Full Name" required type="text">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <input class="form-control" name="email" placeholder="Email" required type="email">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <textarea class="form-control" name="message" placeholder="Message" required rows="3"></textarea>
                                </div>
                            </div>
                            <button class="btn btn-success btn-lg btn-block mt-3" type="submit">Send Now</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <!-- Contact Section Ends -->

    <!-- Bootstrap JS (includes Popper.js) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0-alpha1/js/bootstrap.bundle.min.js"></script>

</body>
</html>
<?php include("./footer.php"); ?>