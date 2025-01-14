<?php
include("./header.php");
// Fetch all heroes
$heroes = getAllHeroes();
?>

<div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-indicators">
        <?php foreach ($heroes as $index => $hero): ?>
            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="<?= $index ?>" class="<?= $index === 0 ? 'active' : '' ?>" aria-label="Slide <?= $index + 1 ?>"></button>
        <?php endforeach; ?>
    </div>

    <div class="carousel-inner">
        <?php foreach ($heroes as $index => $hero): ?>
            <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                <div class="carousel-image-wrapper position-relative overflow-hidden">
                    <img src="./images/heroes/<?= htmlspecialchars($hero['heroImg']) ?>" class="d-block w-100 img-fluid" alt="<?= htmlspecialchars($hero['heroTitle']) ?>" style="object-fit: cover; height: 100vh;">
                    <div class="carousel-caption d-flex justify-content-center align-items-center position-absolute top-50 start-50 translate-middle w-100 text-center">
                        <div>
                            <h5 class="text-white"><?= htmlspecialchars($hero['heroTitle']) ?></h5>
                            <p class="text-white"><?= htmlspecialchars($hero['heroMessage']) ?></p>
                            <p><a href="hero-content.php?id=<?= base64_encode($hero['heroId']) ?>" class="btn btn-warning mt-3">Learn More</a></p>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Bootstrap JS and Popper.js -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize the carousel manually after DOM content is loaded
        var myCarousel = new bootstrap.Carousel(document.getElementById('carouselExampleIndicators'), {
            interval: 5000, // Optional: interval for automatic slide transition (in milliseconds)
            ride: 'carousel' // Start the carousel automatically
        });
    });
</script>