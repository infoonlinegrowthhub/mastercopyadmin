<?php
session_start();
require 'config/config.php'; // Include your database configuration
include 'header.php'; // Include the header
include 'navbar.php'; // Include the navigation bar
?>

        
        <!-- Page Header Start -->
        <div class="container-fluid page-header py-5">
            <div class="container text-center py-5">
                <h1 class="display-2 text-white mb-4 animated slideInDown">404 Error</h1>
                <nav aria-label="breadcrumb animated slideInDown">
                    <ol class="breadcrumb justify-content-center mb-0">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item"><a href="#">Pages</a></li>
                        <li class="breadcrumb-item" aria-current="page">404 Error</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!-- Page Header End -->


        <?php 
// Fetch data from the facts table
$stmt = $pdo->query("SELECT * FROM facts LIMIT 1");
$fact = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!-- Fact Start -->
<div class="container-fluid bg-secondary py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 wow fadeIn" data-wow-delay=".1s">
                <div class="d-flex counter">
                    <h1 class="me-3 text-primary counter-value"><?= htmlspecialchars($fact['success']) ?></h1>
                    <h5 class="text-white mt-1">Success in getting happy customers</h5>
                </div>
            </div>
            <div class="col-lg-3 wow fadeIn" data-wow-delay=".3s">
                <div class="d-flex counter">
                    <h1 class="me-3 text-primary counter-value"><?= htmlspecialchars($fact['businesses']) ?></h1>
                    <h5 class="text-white mt-1">Thousands of successful businesses</h5>
                </div>
            </div>
            <div class="col-lg-3 wow fadeIn" data-wow-delay=".5s">
                <div class="d-flex counter">
                    <h1 class="me-3 text-primary counter-value"><?= htmlspecialchars($fact['clients']) ?></h1>
                    <h5 class="text-white mt-1">Total clients who love Online Growth Hub</h5>
                </div>
            </div>
            <div class="col-lg-3 wow fadeIn" data-wow-delay=".7s">
                <div class="d-flex counter">
                    <h1 class="me-3 text-primary counter-value"><?= htmlspecialchars($fact['stars']) ?></h1>
                    <h5 class="text-white mt-1">Stars reviews given by satisfied clients</h5>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Fact End -->


        <!-- 404 Start -->
        <div class="container-fluid py-5 my-5 wow fadeIn" data-wow-delay="0.3s">
            <div class="container text-center py-5">
                <div class="row justify-content-center">
                    <div class="col-lg-6">
                        <i class="bi bi-exclamation-triangle display-1 text-primary"></i>
                        <h1 class="display-1">404</h1>
                        <h1 class="mb-4">Page Not Found</h1>
                        <p class="mb-4">We’re sorry, the page you have looked for does not exist in our website! Maybe go to our home page or try to use a search?</p>
                        <a class="btn btn-primary rounded-pill py-3 px-5" href="">Go Back To Home</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- 404 End -->


        <?php
include 'footer.php'; // Include the footer
?>
