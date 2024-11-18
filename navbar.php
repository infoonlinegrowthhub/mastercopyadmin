<?php 
// // Fetching dynamic content (add this code at the top of your page)
// require 'config/config.php'; // Include your database configuration file

try {
    $stmt = $pdo->query("SELECT * FROM topbar LIMIT 1"); // Adjust the table name to 'topbar'
    $topbar = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error fetching topbar data: " . $e->getMessage();
}

// Set variables for easy access
$address = $topbar['address'] ?? 'Default Address';
$email = $topbar['email'] ?? 'Default Email';
$company_name = $topbar['company_name'] ?? 'Default Company Name';
$facebook_link = $topbar['facebook_link'] ?? '#';
$twitter_link = $topbar['twitter_link'] ?? '#';
$linkedin_link = $topbar['linkedin_link'] ?? '#';
$instagram_link = $topbar['instagram_link'] ?? '#';
?>

<!-- Topbar Start -->
<div class="container-fluid bg-dark py-2 d-none d-md-flex">
    <div class="container">
        <div class="d-flex justify-content-between topbar">
            <div class="top-info">
                <small class="me-3 text-white-50">
                    <a href="#"><i class="fas fa-map-marker-alt me-2 text-secondary"></i></a>
                    <?php echo htmlspecialchars($address); ?> <!-- Dynamic Address -->
                </small>
                <small class="me-3 text-white-50">
                    <a href="mailto:<?php echo htmlspecialchars($email); ?>"><i class="fas fa-envelope me-2 text-secondary"></i></a>
                    <span><?php echo htmlspecialchars($email); ?></span> <!-- Dynamic Email -->
                </small>
            </div>
            <div id="note" class="text-secondary d-none d-xl-flex">
                <small>Note: We help you to Grow your Business</small>
            </div>
            <div class="top-link">
                <a href="<?php echo htmlspecialchars($facebook_link); ?>" class="bg-light nav-fill btn btn-sm-square rounded-circle">
                    <i class="fab fa-facebook-f text-primary"></i>
                </a>
                <a href="<?php echo htmlspecialchars($twitter_link); ?>" class="bg-light nav-fill btn btn-sm-square rounded-circle">
                    <i class="fab fa-twitter text-primary"></i>
                </a>
                <a href="<?php echo htmlspecialchars($instagram_link); ?>" class="bg-light nav-fill btn btn-sm-square rounded-circle">
                    <i class="fab fa-instagram text-primary"></i>
                </a>
                <a href="<?php echo htmlspecialchars($linkedin_link); ?>" class="bg-light nav-fill btn btn-sm-square rounded-circle me-0">
                    <i class="fab fa-linkedin-in text-primary"></i>
                </a>
            </div>
        </div>
    </div>
</div>
<!-- Topbar End -->

<!-- Navbar Start -->
<div class="container-fluid bg-primary">
    <div class="container">
        <nav class="navbar navbar-dark navbar-expand-lg py-0">

            <!-- Brand Name -->
            <a href="<?php echo BASE_URL; ?>" class="navbar-brand">
                <h1 class="text-white fw-bold d-block"><?php echo htmlspecialchars($company_name); ?></h1>
            </a>

            <!-- Toggler for Mobile -->
            <button type="button" class="navbar-toggler me-0" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Navbar Collapse -->
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <div class="navbar-nav ms-auto mx-xl-auto p-0">
                    <a href="<?php echo BASE_URL; ?>" class="nav-item nav-link active text-secondary">Home</a>
                    <a href="<?php echo BASE_URL; ?>about" class="nav-item nav-link">About</a>
                    <a href="<?php echo BASE_URL; ?>service" class="nav-item nav-link">Services</a>

                    <!-- Projects and Products Dropdown -->
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Projects & Products</a>
                        <div class="dropdown-menu rounded">
                            <a href="<?php echo BASE_URL; ?>project" class="dropdown-item">Projects</a>
                            <a href="<?php echo BASE_URL; ?>products.php" class="dropdown-item">Products</a>
                        </div>
                    </div>

                    <!-- Pages Dropdown -->
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Pages</a>
                        <div class="dropdown-menu rounded">
                            <a href="<?php echo BASE_URL; ?>blog" class="dropdown-item">Our Blog</a>
                            <a href="<?php echo BASE_URL; ?>team" class="dropdown-item">Our Team</a>
                            <a href="<?php echo BASE_URL; ?>testimonial" class="dropdown-item">Testimonial</a>
                            <a href="<?php echo BASE_URL; ?>404" class="dropdown-item">404 Page</a>
                        </div>
                    </div>

                    <a href="<?php echo BASE_URL; ?>contact" class="nav-item nav-link">Contact</a>

                    <!-- Conditional Profile Link -->
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <a href="<?php echo BASE_URL; ?>profile.php" class="nav-item nav-link">Profile</a>
                    <?php endif; ?>

                    <!-- Conditional Login/Logout Button -->
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <a class="nav-item nav-link btn btn-outline-light py-2 px-4 d-lg-inline-flex ms-lg-4 align-items-center" href="<?php echo BASE_URL; ?>user-logout.php">
                            Logout
                            <span class="d-inline-flex align-items-center justify-content-center btn-sm-square bg-primary text-white rounded-circle ms-2">
                                <i class="fa fa-sign-out-alt"></i>
                            </span>
                        </a>
                    <?php else: ?>
                        <a class="nav-item nav-link btn btn-outline-light py-2 px-4 d-lg-inline-flex ms-lg-4 align-items-center" href="<?php echo BASE_URL; ?>user-login.php">
                            Login
                            <span class="d-inline-flex align-items-center justify-content-center btn-sm-square bg-primary text-white rounded-circle ms-2">
                                <i class="fa fa-user"></i>
                            </span>
                        </a>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Call Section for Desktop Only -->
            <div class="d-none d-xl-flex flex-shrink-0 ms-3"> <!-- Added ms-3 for left margin -->
                <div id="phone-tada" class="d-flex align-items-center justify-content-center me-4">
                    <a href="tel:+919032666855" class="position-relative animated tada infinite" aria-label="Call us">
                        <i class="fa fa-phone-alt text-white fa-2x"></i>
                        <div class="position-absolute" style="top: -7px; left: 20px;">
                            <span><i class="fa fa-comment-dots text-secondary"></i></span>
                        </div>
                    </a>
                </div>
                <div class="d-flex flex-column pe-4 border-end">
                    <span class="text-white-50">Have any questions?</span>
                    <span class="text-secondary">Call: <a href="tel:+919032666685" class="text-secondary">+91 9032666855</a></span> <!-- Made the number clickable -->
                </div>
            </div>
        </nav>
    </div>
</div>
<!-- Navbar End -->


