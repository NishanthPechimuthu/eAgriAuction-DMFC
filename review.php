<?php
ob_start();
$reviews = getReviews();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            background: #f8f9fa;
            font-family: 'Montserrat', sans-serif;
        }
        .section-header {
            text-align: center;
            color: #333;
            margin-bottom: 40px;
        }
        .section-header::after {
            content: '';
            display: block;
            width: 100px;
            height: 3px;
            background: #333;
            margin: 10px auto 0;
        }
        .custom-carousel {
            position: relative;
            overflow: hidden;
            width: 100%;
            max-width: 800px;
            margin: auto;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            background: white;
            border-radius: 10px;
        }
        .custom-carousel-inner {
            display: flex;
            transition: transform 0.5s ease-in-out;
        }
        .custom-carousel-item {
            min-width: 100%;
            box-sizing: border-box;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            animation: fadeIn 0.8s ease-in-out;
        }
        .custom-carousel-item img {
            height: 80px;
            width: 80px;
            object-fit: cover;
            border-radius: 50%;
            border: 2px solid rgb(25, 135, 84); /* Success color */
            margin-bottom: 15px;
            transition: transform 0.5s ease-in-out;
        }
        .custom-carousel-item img:hover {
            transform: scale(1.1);
        }
        .bio h2 {
            font-size: 20px;
            color: #333;
        }
        .content {
            overflow: hidden;
        }
        .content p {
            font-size: 16px;
            color: #555;
            text-align: justify;
        }
        .content .fa-quote-left {
            color: rgb(25, 135, 84); /* Success color for quote */
            font-size: 24px;
            margin-right: 10px;
        }
        .carousel-controls {
            position: absolute;
            top: 50%;
            width: 100%;
            display: flex;
            justify-content: space-between;
            transform: translateY(-50%);
        }
        .carousel-controls button {
            background: transparent;
            color: rgb(25, 135, 84,0.5); /* Success color for buttons */
            border: none;
            border-radius: 50%;
            padding: 15px;
            cursor: pointer;
            font-size: 24px;
            transition: opacity 0.3s ease, transform 0.3s ease;
        }
        .carousel-controls button:hover {
            transform: scale(1.2); /* Slight zoom effect */
            opacity: 0.8;
        }
        .carousel-controls button:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

    </style>
</head>
<body>
<section id="testimonials" class="testimonials section-padding">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="section-header text-center pb-5">
                    <h2>Customer Reviews</h2>
                    <p>Here's what our customers are saying about us.</p>
                </div>
            </div>
        </div>
        <div class="custom-carousel">
            <div class="custom-carousel-inner">
                <?php
                // Dynamically generate carousel items
                foreach ($reviews as $review) {
                    ?>
                    <div class="custom-carousel-item">
                        <img src="./images/profiles/<?= htmlspecialchars(getUserImage($review['reviewUserId'])); ?>" alt="User Image">
                        <div>
                            <h2 class="text-center"><?= getUserFullName($review['reviewUserId'])?></h2>
                            <h5 class="text-center text-secondary"><?='@'. getUserName($review['reviewUserId'])?></h5>
                            <div class="content">
                                <p class="text-center"><i class="fa fa-quote-left"></i><?php echo htmlspecialchars($review['reviewMessage']); ?></p>
                            </div>
                        </div>
                    </div>
                    <?php
                }
                ?>
            </div>
            <!-- Custom carousel controls -->
            <div class="carousel-controls">
                <button id="prevBtn">
                    <i class="fa fa-chevron-left"></i>
                </button>
                <button id="nextBtn">
                    <i class="fa fa-chevron-right"></i>
                </button>
            </div>
        </div>
    </div>
</section>

<script>
    const carouselInner = document.querySelector('.custom-carousel-inner');
    const carouselItems = document.querySelectorAll('.custom-carousel-item');
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');

    let currentIndex = 0;

    function updateCarousel() {
        const offset = -currentIndex * 100;
        carouselInner.style.transform = `translateX(${offset}%)`;
    }

    function loopCarousel() {
        // When reaching the end, reset to the first item
        if (currentIndex >= carouselItems.length) {
            carouselInner.style.transition = 'none';
            currentIndex = 0;
            updateCarousel();
            setTimeout(() => {
                carouselInner.style.transition = 'transform 0.5s ease-in-out';
            }, 50);
        }

        // When reaching the start from the previous button, move to the last item
        if (currentIndex < 0) {
            carouselInner.style.transition = 'none';
            currentIndex = carouselItems.length - 1;
            updateCarousel();
            setTimeout(() => {
                carouselInner.style.transition = 'transform 0.5s ease-in-out';
            }, 50);
        }
    }

    nextBtn.addEventListener('click', () => {
        currentIndex++;
        updateCarousel();
        loopCarousel();
    });

    prevBtn.addEventListener('click', () => {
        currentIndex--;
        updateCarousel();
        loopCarousel();
    });

    // Automatic slide every 5 seconds
    setInterval(() => {
        currentIndex++;
        updateCarousel();
        loopCarousel();
    }, 5000);

    // Initialize carousel
    updateCarousel();
</script>
</body>
</html>
<?php ob_end_flush(); ?>