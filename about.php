<?php
session_start();
require 'config/config.php'; // Include your database configuration
include 'header.php'; // Include the header
include 'navbar.php'; // Include the navigation bar
?>
        
        <!-- Page Header Start -->
        <div class="container-fluid page-header py-5">
            <div class="container text-center py-5">
                <h1 class="display-2 text-white mb-4 animated slideInDown">About Us</h1>
                <nav aria-label="breadcrumb animated slideInDown">
                    <ol class="breadcrumb justify-content-center mb-0">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item"><a href="#">Pages</a></li>
                        <li class="breadcrumb-item" aria-current="page">About</li>
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
        <div class="row text-center">
            <div class="col-6 col-sm-3 wow fadeIn" data-wow-delay=".1s">
                <div class="d-flex flex-column align-items-center">
                    <h1 class="text-primary counter-value"><?= htmlspecialchars($fact['success']) ?></h1>
                    <h5 class="text-white mt-1">Success in getting happy customers</h5>
                </div>
            </div>
            <div class="col-6 col-sm-3 wow fadeIn" data-wow-delay=".3s">
                <div class="d-flex flex-column align-items-center">
                    <h1 class="text-primary counter-value"><?= htmlspecialchars($fact['businesses']) ?></h1>
                    <h5 class="text-white mt-1">Thousands of successful businesses</h5>
                </div>
            </div>
            <div class="col-6 col-sm-3 wow fadeIn" data-wow-delay=".5s">
                <div class="d-flex flex-column align-items-center">
                    <h1 class="text-primary counter-value"><?= htmlspecialchars($fact['clients']) ?></h1>
                    <h5 class="text-white mt-1">Total clients who love Online Growth Hub</h5>
                </div>
            </div>
            <div class="col-6 col-sm-3 wow fadeIn" data-wow-delay=".7s">
                <div class="d-flex flex-column align-items-center">
                    <h1 class="text-primary counter-value"><?= htmlspecialchars($fact['stars']) ?></h1>
                    <h5 class="text-white mt-1">Stars reviews given by satisfied clients</h5>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Fact End -->


<!-- About Start -->
<div class="container-fluid py-5 my-5">
    <div class="container pt-5">
        <div class="row g-5">
            <div class="col-lg-5 col-md-6 col-sm-12 wow fadeIn" data-wow-delay=".3s">
                <div class="h-100 position-relative">
                    <!-- Fetching images from the database -->
                    <?php
                    // Fetch the latest about entry (you can adjust the query as needed)
                    $statement = $pdo->prepare("SELECT image1, image2 FROM about ORDER BY id DESC LIMIT 1");
                    $statement->execute();
                    $about_entry = $statement->fetch(PDO::FETCH_ASSOC);
                    ?>
                    <img src="<?php echo htmlspecialchars($about_entry['image1']); ?>" class="img-fluid w-75 rounded" alt="" style="margin-bottom: 25%;">
                    <div class="position-absolute w-75" style="top: 25%; left: 25%;">
                        <img src="<?php echo htmlspecialchars($about_entry['image2']); ?>" class="img-fluid w-100 rounded" alt="">
                    </div>
                </div>
            </div>
            <div class="col-lg-7 col-md-6 col-sm-12 wow fadeIn" data-wow-delay=".5s">
                <h5 class="text-primary">About Us</h5>
                <h1 class="mb-4">
                    <?php
                    // Fetching the title from the database
                    $statement = $pdo->prepare("SELECT title FROM about ORDER BY id DESC LIMIT 1");
                    $statement->execute();
                    $about_title = $statement->fetchColumn();
                    echo htmlspecialchars($about_title);
                    ?>
                </h1>
                <p>
                    <?php
                    // Fetching the first description from the database
                    $statement = $pdo->prepare("SELECT description1 FROM about ORDER BY id DESC LIMIT 1");
                    $statement->execute();
                    $about_description1 = $statement->fetchColumn();
                    echo htmlspecialchars($about_description1);
                    ?>
                </p>
                <p class="mb-4">
                    <?php
                    // Fetching the second description from the database
                    $statement = $pdo->prepare("SELECT description2 FROM about ORDER BY id DESC LIMIT 1");
                    $statement->execute();
                    $about_description2 = $statement->fetchColumn();
                    echo htmlspecialchars($about_description2);
                    ?>
                </p>
                <a href="" class="btn btn-secondary rounded-pill px-5 py-3 text-white">More Details</a>
            </div>
        </div>
    </div>
</div>
<!-- About End -->
<!-- Team Start -->
<div class="container-fluid py-5 mb-5 team">
    <div class="container">
        <?php
        // Fetching the main and sub headings from the team table (assuming same for all members)
        $headingStatement = $pdo->prepare("SELECT main_heading, sub_heading FROM team LIMIT 1");
        $headingStatement->execute();
        $headings = $headingStatement->fetch(PDO::FETCH_ASSOC);
        ?>
        <div class="text-center mx-auto pb-5 wow fadeIn" data-wow-delay=".3s" style="max-width: 600px;">
            <h5 class="text-primary"><?php echo htmlspecialchars($headings['main_heading']); ?></h5>
            <h1><?php echo htmlspecialchars($headings['sub_heading']); ?></h1>
        </div>
        <div class="owl-carousel team-carousel wow fadeIn" data-wow-delay=".5s">
            <?php
            // Fetching Team Entries for Display
            $statement = $pdo->prepare("SELECT * FROM team");
            $statement->execute();
            $team_entries = $statement->fetchAll(PDO::FETCH_ASSOC);
            
            // Loop through each team entry
            foreach ($team_entries as $entry) {
                ?>
                <div class="rounded team-item">
                    <div class="team-content">
                        <div class="team-img-icon">
                            <div class="team-img rounded-circle">
                                <img src="<?php echo htmlspecialchars($entry['image']); ?>" class="img-fluid w-100 rounded-circle" alt="<?php echo htmlspecialchars($entry['full_name']); ?>">
                            </div>
                            <div class="team-name text-center py-3">
                                <h4 class=""><?php echo htmlspecialchars($entry['full_name']); ?></h4>
                                <p class="m-0"><?php echo htmlspecialchars($entry['designation']); ?></p>
                            </div>
                            <div class="team-icon d-flex justify-content-center pb-4">
                                <?php if (!empty($entry['facebook_link'])): ?>
                                    <a class="btn btn-square btn-secondary text-white rounded-circle m-1" href="<?php echo htmlspecialchars($entry['facebook_link']); ?>" target="_blank"><i class="fab fa-facebook-f"></i></a>
                                <?php endif; ?>
                                <?php if (!empty($entry['twitter_link'])): ?>
                                    <a class="btn btn-square btn-secondary text-white rounded-circle m-1" href="<?php echo htmlspecialchars($entry['twitter_link']); ?>" target="_blank"><i class="fab fa-twitter"></i></a>
                                <?php endif; ?>
                                <?php if (!empty($entry['instagram_link'])): ?>
                                    <a class="btn btn-square btn-secondary text-white rounded-circle m-1" href="<?php echo htmlspecialchars($entry['instagram_link']); ?>" target="_blank"><i class="fab fa-instagram"></i></a>
                                <?php endif; ?>
                                <?php if (!empty($entry['linkedin_link'])): ?>
                                    <a class="btn btn-square btn-secondary text-white rounded-circle m-1" href="<?php echo htmlspecialchars($entry['linkedin_link']); ?>" target="_blank"><i class="fab fa-linkedin-in"></i></a>
                                <?php endif; ?>
                                <?php if (!empty($entry['youtube_link'])): ?>
                                    <a class="btn btn-square btn-secondary text-white rounded-circle m-1" href="<?php echo htmlspecialchars($entry['youtube_link']); ?>" target="_blank"><i class="fab fa-youtube"></i></a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
            }
            ?>
        </div>
    </div>
</div>
<!-- Team End -->

        <?php
include 'footer.php'; // Include the footer
?>