<?php
session_start();
require 'config/config.php'; // Include your database configuration
include 'header.php'; // Include the header
include 'navbar.php'; // Include the navigation bar
?>

<!-- Page Header Start -->
<div class="container-fluid page-header py-5">
    <div class="container text-center py-5">
        <h1 class="display-2 text-white mb-4 animated slideInDown">Our Blog</h1>
        <nav aria-label="breadcrumb animated slideInDown">
            <ol class="breadcrumb justify-content-center mb-0">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item"><a href="#">Pages</a></li>
                <li class="breadcrumb-item" aria-current="page">Blog</li>
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

<?php
// Prepare SQL query to get published posts with error handling
try {
    $sql = "SELECT id, title, slug, image, category, author, created_at, short_description, category_slug, shares, comments FROM posts WHERE status = 'published'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error fetching posts: " . htmlspecialchars($e->getMessage());
}

// Blog Start
?>
<div class="container-fluid blog py-5 my-5">
    <div class="container py-5">
        <div class="text-center mx-auto pb-5 wow fadeIn" data-wow-delay=".3s" style="max-width: 600px;">
            <h5 class="text-primary">Our Blog</h5>
            <h1>Latest Blog & News</h1>
        </div>
        <div class="row g-5 justify-content-center">
            <?php if (empty($posts)): ?>
                <p>No posts available at the moment.</p>
            <?php else: ?>
                <?php foreach ($posts as $post): ?>
                    <div class="col-lg-6 col-xl-4 wow fadeIn" data-wow-delay=".3s">
                        <div class="blog-item position-relative bg-light rounded">
                            <img src="<?php echo BASE_URL . 'img/' . htmlspecialchars($post['image']); ?>" class="img-fluid w-100 rounded-top" alt="<?php echo htmlspecialchars($post['title']); ?>">
                            <span class="position-absolute px-4 py-3 bg-primary text-white rounded" style="top: -28px; right: 20px;"><?php echo htmlspecialchars($post['category']); ?></span>
                            <div class="blog-btn d-flex justify-content-between position-relative px-3" style="margin-top: -75px;">
                                <div class="blog-icon btn btn-secondary px-3 rounded-pill my-auto">
                                    <a href="<?php echo htmlspecialchars(isset($post['category_slug']) ? $post['category_slug'] : 'default-category'); ?>/<?php echo htmlspecialchars($post['slug']); ?>" class="btn text-white">Read More</a>
                                </div>
                                <div class="blog-btn-icon btn btn-secondary px-4 py-3 rounded-pill">
                                    <div class="blog-icon-1">
                                        <p class="text-white px-2">Share<i class="fa fa-arrow-right ms-3"></i></p>
                                    </div>
                                    <div class="blog-icon-2">
                                        <a href="#" class="btn me-1"><i class="fab fa-facebook-f text-white"></i></a>
                                        <a href="#" class="btn me-1"><i class="fab fa-twitter text-white"></i></a>
                                        <a href="#" class="btn me-1"><i class="fab fa-instagram text-white"></i></a>
                                    </div>
                                </div>
                            </div>
                            <div class="blog-content text-center position-relative px-3" style="margin-top: -25px;">
                                <img src="img/admin.jpg" class="img-fluid rounded-circle border border-4 border-white mb-3" alt="Author Image">
                                <h5><?php echo htmlspecialchars($post['author']); ?></h5>
                                <span class="text-secondary"><?php echo date('d M Y', strtotime($post['created_at'])); ?></span>
                                <p class="py-2"><?php echo htmlspecialchars($post['short_description']); ?></p>
                            </div>
                            <div class="blog-coment d-flex justify-content-between px-4 py-2 border bg-primary rounded-bottom">
                                <small class="text-white"><i class="fas fa-share me-2 text-secondary"></i><?php echo (int)$post['shares']; ?> Shares</small>
                                <small class="text-white"><i class="fa fa-comments me-2 text-secondary"></i><?php echo (int)$post['comments']; ?> Comments</small>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>
<!-- Blog End -->

<?php include 'footer.php'; // Include the footer ?>
