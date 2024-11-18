<?php
session_start();
require 'config/config.php'; // Include your database configuration
include 'header.php'; // Include the header
include 'navbar.php'; // Include the navigation bar

// Get the slug from the URL
$slug = isset($_GET['slug']) ? $_GET['slug'] : '';

// Prepare SQL query to get the post
$sql = "SELECT * FROM posts WHERE slug = :slug AND status = 'published'";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':slug', $slug);
$stmt->execute();

// Fetch the post
$post = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$post) {
    echo '<div class="container"><h2>Post not found!</h2></div>';
    include 'footer.php'; // Include the footer
    exit;
}

// Increase view count
$updateViews = "UPDATE posts SET views = views + 1 WHERE id = :id";
$updateStmt = $pdo->prepare($updateViews);
$updateStmt->bindParam(':id', $post['id']);
$updateStmt->execute();

// Check if a comment was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['comment'])) {
    // Get the posted data
    $name = trim($_POST['name']);
    $comment = trim($_POST['comment']);
    $post_id = $post['id']; // Use the current post ID

    // Insert the comment into the comments table
    $insertComment = "INSERT INTO comments (post_id, name, comment) VALUES (:post_id, :name, :comment)";
    $insertStmt = $pdo->prepare($insertComment);
    $insertStmt->bindParam(':post_id', $post_id);
    $insertStmt->bindParam(':name', $name);
    $insertStmt->bindParam(':comment', $comment);

    if ($insertStmt->execute()) {
        // Update the comments count in the posts table
        $updateComments = "UPDATE posts SET comments = comments + 1 WHERE id = :post_id";
        $updateStmt = $pdo->prepare($updateComments);
        $updateStmt->bindParam(':post_id', $post_id);
        $updateStmt->execute();

        echo '<div class="alert alert-success">Comment submitted successfully!</div>';
    } else {
        echo '<div class="alert alert-danger">Failed to submit comment!</div>';
    }
}
?>

<!-- Page Header Start -->
<div class="container-fluid page-header py-5 bg-primary">
    <div class="container text-center py-5">
        <h1 class="display-2 text-white mb-4 animated slideInDown"><?php echo htmlspecialchars($post['title']); ?></h1>
        <nav aria-label="breadcrumb animated slideInDown">
            <ol class="breadcrumb justify-content-center mb-0">
                <li class="breadcrumb-item"><a href="index.php" class="text-white">Home</a></li>
                <li class="breadcrumb-item"><a href="blog.php" class="text-white">Blog</a></li>
                <li class="breadcrumb-item active text-white" aria-current="page"><?php echo htmlspecialchars($post['title']); ?></li>
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

<!-- Post Content Start -->
<div class="container py-5">
    <div class="row">
        <!-- Main Content Start -->
        <div class="col-lg-8">
            <div class="post-item bg-light rounded mb-4">
                <img src="<?php echo BASE_URL . 'img/' . htmlspecialchars($post['image']); ?>" class="img-fluid w-100 rounded-top" alt="<?php echo htmlspecialchars($post['title']); ?>">
                <div class="p-4">
                    <h3><?php echo htmlspecialchars($post['title']); ?></h3>
                    <div class="d-flex justify-content-between mb-3">
                        <small class="text-muted">By <?php echo htmlspecialchars($post['author']); ?> | <?php echo date('d M Y', strtotime($post['created_at'])); ?> | <?php echo (int)$post['views']; ?> Views | <?php echo (int)$post['comments']; ?> Comments</small>
                    </div>
                    <p><?php echo nl2br(htmlspecialchars($post['short_description'])); ?></p>
                    <div class="post-content">
                        <?php echo $post['content']; ?>
                    </div>
                    <!-- Social Share Buttons -->
                    <div class="social-share mt-3">
                        <h5 class="mb-3">Share this post:</h5>
                        <div class="d-flex flex-wrap justify-content-start">
                            <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(BASE_URL . $post['category_slug'] . '/' . $post['slug']); ?>" 
                            target="_blank" 
                            class="btn btn-primary me-2 mb-2 share-btn" 
                            style="background-color: #3b5998; border-color: #3b5998; color: #ffffff;">
                            <i class="fab fa-facebook-f"></i> Facebook
                            </a>
                            <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode(BASE_URL . $post['category_slug'] . '/' . $post['slug']); ?>" 
                            target="_blank" 
                            class="btn btn-info me-2 mb-2 share-btn" 
                            style="background-color: #1da1f2; border-color: #1da1f2; color: #ffffff;">
                            <i class="fab fa-twitter"></i> X
                            </a>
                            <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo urlencode(BASE_URL . $post['category_slug'] . '/' . $post['slug']); ?>" 
                            target="_blank" 
                            class="btn btn-linkedin me-2 mb-2 share-btn" 
                            style="background-color: #0077b5; border-color: #0077b5; color: #ffffff;">
                            <i class="fab fa-linkedin-in"></i> LinkedIn
                            </a>
                            <a href="https://api.whatsapp.com/send?text=<?php echo urlencode(BASE_URL . $post['category_slug'] . '/' . $post['slug']); ?>" 
                            target="_blank" 
                            class="btn btn-success me-2 mb-2 share-btn" 
                            style="background-color: #25D366; border-color: #25D366; color: #ffffff;">
                            <i class="fab fa-whatsapp"></i> WhatsApp
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Back Button -->
            <a href="<?php echo BASE_URL . 'blog.php'; ?>" class="btn btn-secondary mb-3"><i class="fas fa-arrow-left"></i> Back to Blog</a>
            
            <!-- Comment Form Start -->
            <div class="bg-light p-4 rounded mb-5">
                <h4>Leave a Comment:</h4>
                <form action="" method="POST">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="comment" class="form-label">Comment</label>
                        <textarea class="form-control" id="comment" name="comment" rows="3" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit Comment</button>
                </form>
            </div>
            <!-- Comment Form End -->

            <!-- Comments Section Start -->
            <div class="bg-light p-4 rounded">
                <h4>Comments (<?php echo $post['comments']; ?>)</h4>
                <?php
                // Fetch comments for the current post
                $commentQuery = "SELECT * FROM comments WHERE post_id = :post_id ORDER BY created_at DESC";
                $commentStmt = $pdo->prepare($commentQuery);
                $commentStmt->bindParam(':post_id', $post['id']);
                $commentStmt->execute();
                $comments = $commentStmt->fetchAll(PDO::FETCH_ASSOC);

                foreach ($comments as $comment) {
                    echo '<div class="comment mb-3">';
                    echo '<h6 class="fw-bold">' . htmlspecialchars($comment['name']) . '</h6>';
                    echo '<p>' . htmlspecialchars($comment['comment']) . '</p>';
                    echo '<small class="text-muted">' . date('d M Y, H:i', strtotime($comment['created_at'])) . '</small>';
                    echo '</div>';
                }
                ?>
            </div>
            <!-- Comments Section End -->
        </div>
        <!-- Main Content End -->

        <!-- Sidebar Start -->
        <div class="col-lg-4">
            <div class="bg-light p-4 rounded mb-5">
                <h4>Recent Posts</h4>
                <?php
                // Fetch recent posts
                $recentPostsQuery = "SELECT * FROM posts WHERE status = 'published' ORDER BY created_at DESC LIMIT 5";
                $recentPostsStmt = $pdo->prepare($recentPostsQuery);
                $recentPostsStmt->execute();
                $recentPosts = $recentPostsStmt->fetchAll(PDO::FETCH_ASSOC);

                foreach ($recentPosts as $recentPost) {
                    echo '<div class="recent-post mb-3 d-flex align-items-center p-3 border rounded shadow-sm hover-effect">';
                    echo '<a href="' . BASE_URL . $recentPost['category_slug'] . '/' . $recentPost['slug'] . '" class="text-dark me-3">';
                    echo '<img src="' . BASE_URL . 'img/' . htmlspecialchars($recentPost['image']) . '" class="img-fluid rounded" alt="' . htmlspecialchars($recentPost['title']) . '" style="width: 80px; height: 80px; object-fit: cover;">'; // Thumbnail image
                    echo '</a>';
                    echo '<div>';
                    echo '<h6 class="mb-1">' . htmlspecialchars($recentPost['title']) . '</h6>';
                    echo '<small class="text-muted">' . date('d M Y', strtotime($recentPost['created_at'])) . '</small>';
                    echo '</div>';
                    echo '</div>';
                }
                ?>
            </div>
        </div>
        <!-- Sidebar End -->


    </div>
</div>
<!-- Post Content End -->


<?php include 'footer.php'; // Include the footer ?>
