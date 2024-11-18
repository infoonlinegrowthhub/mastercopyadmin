<?php
include 'layouts/top.php';

// Configuration
class PostManager {
    private $pdo;
    private $target_dir;
    private $allowed_image_types;
    private $max_image_size;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->target_dir = "../img/";
        $this->allowed_image_types = ['image/jpeg', 'image/png', 'image/gif'];
        $this->max_image_size = 5 * 1024 * 1024; // 5MB
    }
    
    // Validate and sanitize input
    private function sanitizeInput($data) {
        return htmlspecialchars(strip_tags(trim($data)));
    }
    
    private function generateSlug($title) {
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));
        return $slug;
    }
    
    // Handle image upload
    private function handleImageUpload($file, $old_image = null) {
        if (!isset($file['name']) || empty($file['name'])) {
            return $old_image;
        }

        // Validate image
        if (!in_array($file['type'], $this->allowed_image_types)) {
            throw new Exception("Invalid image type. Allowed types: JPG, PNG, GIF");
        }

        if ($file['size'] > $this->max_image_size) {
            throw new Exception("Image size too large. Maximum size: 5MB");
        }

        // Generate unique filename
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid() . '.' . $extension;
        $target_file = $this->target_dir . $filename;

        // Create directory if it doesn't exist
        if (!is_dir($this->target_dir)) {
            mkdir($this->target_dir, 0755, true);
        }

        // Delete old image if exists
        if ($old_image && file_exists($old_image)) {
            unlink($old_image);
        }

        // Move uploaded file
        if (!move_uploaded_file($file['tmp_name'], $target_file)) {
            throw new Exception("Failed to upload image");
        }

        return $target_file;
    }
    
    // Create post
    public function createPost($data, $file) {
        try {
            // Validate required fields
            $required_fields = ['title', 'author', 'status'];
            foreach ($required_fields as $field) {
                if (empty($data[$field])) {
                    throw new Exception("$field is required");
                }
            }

            // Generate slug if not provided
            $slug = !empty($data['slug']) ? $this->sanitizeInput($data['slug']) : 
                   $this->generateSlug($data['title']);

            // Check if slug exists
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM posts WHERE slug = ?");
            $stmt->execute([$slug]);
            if ($stmt->fetchColumn() > 0) {
                $slug .= '-' . uniqid();
            }

            // Handle image upload
            $image_url = $this->handleImageUpload($file);

            // Prepare data for insertion
            $sql = "INSERT INTO posts (
                title, slug, content, short_description, image, 
                category, category_slug, author, meta_title, 
                meta_description, keywords, status, created_at, updated_at
            ) VALUES (
                ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW()
            )";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                $this->sanitizeInput($data['title']),
                $slug,
                $data['content'],
                $data['short_description'],
                $image_url,
                $this->sanitizeInput($data['category']),
                $this->sanitizeInput($data['category_slug']),
                $this->sanitizeInput($data['author']),
                $this->sanitizeInput($data['meta_title']),
                $this->sanitizeInput($data['meta_description']),
                $this->sanitizeInput($data['keywords']),
                $this->sanitizeInput($data['status'])
            ]);

            return ["success" => "Post created successfully!"];
        } catch (Exception $e) {
            return ["error" => $e->getMessage()];
        }
    }
    
    // Update post
    public function updatePost($id, $data, $file) {
        try {
            // Get existing post
            $stmt = $this->pdo->prepare("SELECT * FROM posts WHERE id = ?");
            $stmt->execute([$id]);
            $post = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$post) {
                throw new Exception("Post not found");
            }

            // Handle image upload if new image provided
            $image_url = $this->handleImageUpload($file, $post['image']);

            // Update post
            $sql = "UPDATE posts SET 
                    title = ?, slug = ?, content = ?, short_description = ?,
                    image = ?, category = ?, category_slug = ?, author = ?,
                    meta_title = ?, meta_description = ?, keywords = ?,
                    status = ?, updated_at = NOW()
                    WHERE id = ?";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                $this->sanitizeInput($data['title']),
                $this->sanitizeInput($data['slug']),
                $data['content'],
                $data['short_description'],
                $image_url,
                $this->sanitizeInput($data['category']),
                $this->sanitizeInput($data['category_slug']),
                $this->sanitizeInput($data['author']),
                $this->sanitizeInput($data['meta_title']),
                $this->sanitizeInput($data['meta_description']),
                $this->sanitizeInput($data['keywords']),
                $this->sanitizeInput($data['status']),
                $id
            ]);

            return ["success" => "Post updated successfully!"];
        } catch (Exception $e) {
            return ["error" => $e->getMessage()];
        }
    }
    
    // Delete post
    public function deletePost($id) {
        try {
            // Get post image
            $stmt = $this->pdo->prepare("SELECT image FROM posts WHERE id = ?");
            $stmt->execute([$id]);
            $post = $stmt->fetch(PDO::FETCH_ASSOC);

            // Delete image file
            if ($post && $post['image'] && file_exists($post['image'])) {
                unlink($post['image']);
            }

            // Delete post
            $stmt = $this->pdo->prepare("DELETE FROM posts WHERE id = ?");
            $stmt->execute([$id]);

            return ["success" => "Post deleted successfully!"];
        } catch (Exception $e) {
            return ["error" => $e->getMessage()];
        }
    }
    
    // Get all posts
    public function getAllPosts() {
        try {
            return $this->pdo->query("SELECT * FROM posts ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }
    
    // Get single post
    public function getPost($id) {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM posts WHERE id = ?");
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return null;
        }
    }
}

// Initialize PostManager
$postManager = new PostManager($pdo);
$message = [];

// Handle form submissions
if (isset($_POST['form_create'])) {
    $message = $postManager->createPost($_POST, $_FILES['image']);
} elseif (isset($_POST['form_edit'])) {
    $message = $postManager->updatePost($_POST['id'], $_POST, $_FILES['image']);
} elseif (isset($_GET['delete'])) {
    $message = $postManager->deletePost($_GET['delete']);
}

// Get post for editing if edit mode
$editPost = null;
if (isset($_GET['edit'])) {
    $editPost = $postManager->getPost($_GET['edit']);
}

// Get all posts
$posts = $postManager->getAllPosts();
?>

<!-- HTML Form and Table Structure -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1><?php echo $editPost ? 'Edit Post' : 'Post Management'; ?></h1>
        </div>

        <div class="section-body">
            <?php if (isset($message['success'])): ?>
                <div class="alert alert-success"><?php echo $message['success']; ?></div>
            <?php endif; ?>
            <?php if (isset($message['error'])): ?>
                <div class="alert alert-danger"><?php echo $message['error']; ?></div>
            <?php endif; ?>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="" method="post" enctype="multipart/form-data">
                                <?php if ($editPost): ?>
                                    <input type="hidden" name="form_edit" value="1">
                                    <input type="hidden" name="id" value="<?php echo $editPost['id']; ?>">
                                <?php else: ?>
                                    <input type="hidden" name="form_create" value="1">
                                <?php endif; ?>

                                <div class="mb-4">
                                    <label>Title *</label>
                                    <input type="text" class="form-control" name="title" required 
                                           value="<?php echo $editPost ? htmlspecialchars($editPost['title']) : ''; ?>">
                                </div>

                                <div class="mb-4">
                                    <label>Slug</label>
                                    <input type="text" class="form-control" name="slug"
                                           value="<?php echo $editPost ? htmlspecialchars($editPost['slug']) : ''; ?>">
                                </div>

                                <div class="mb-4">
                                    <label>Content</label>
                                    <textarea class="form-control editor" name="content"><?php echo $editPost ? $editPost['content'] : ''; ?></textarea>
                                </div>

                                <div class="mb-4">
                                    <label>Short Description</label>
                                    <textarea class="form-control" name="short_description"><?php echo $editPost ? htmlspecialchars($editPost['short_description']) : ''; ?></textarea>
                                </div>

                                <div class="mb-4">
                                    <label>Category</label>
                                    <input type="text" class="form-control" name="category"
                                           value="<?php echo $editPost ? htmlspecialchars($editPost['category']) : ''; ?>">
                                </div>

                                <div class="mb-4">
                                    <label>Category Slug</label>
                                    <input type="text" class="form-control" name="category_slug"
                                           value="<?php echo $editPost ? htmlspecialchars($editPost['category_slug']) : ''; ?>">
                                </div>

                                <div class="mb-4">
                                    <label>Author *</label>
                                    <input type="text" class="form-control" name="author" required
                                           value="<?php echo $editPost ? htmlspecialchars($editPost['author']) : ''; ?>">
                                </div>

                                <div class="mb-4">
                                    <label>Meta Title</label>
                                    <input type="text" class="form-control" name="meta_title"
                                           value="<?php echo $editPost ? htmlspecialchars($editPost['meta_title']) : ''; ?>">
                                </div>

                                <div class="mb-4">
                                    <label>Meta Description</label>
                                    <textarea class="form-control" name="meta_description"><?php echo $editPost ? htmlspecialchars($editPost['meta_description']) : ''; ?></textarea>
                                </div>

                                <div class="mb-4">
                                    <label>Keywords</label>
                                    <input type="text" class="form-control" name="keywords"
                                           value="<?php echo $editPost ? htmlspecialchars($editPost['keywords']) : ''; ?>">
                                </div>

                                <div class="mb-4">
                                    <label>Status *</label>
                                    <select class="form-control" name="status" required>
                                        <option value="published" <?php echo ($editPost && $editPost['status'] == 'published') ? 'selected' : ''; ?>>Published</option>
                                        <option value="draft" <?php echo ($editPost && $editPost['status'] == 'draft') ? 'selected' : ''; ?>>Draft</option>
                                    </select>
                                </div>

                                <div class="mb-4">
                                    <label>Image <?php echo $editPost ? '' : '*'; ?></label>
                                    <?php if ($editPost && $editPost['image']): ?>
                                        <div class="mb-2">
                                            <img src="<?php echo htmlspecialchars($editPost['image']); ?>" alt="Current Image" width="200">
                                        </div>
                                    <?php endif; ?>
                                    <input type="file" class="form-control" name="image" <?php echo $editPost ? '' : 'required'; ?>>
                                </div>

                                <button type="submit" class="btn btn-primary">
                                    <?php echo $editPost ? 'Update Post' : 'Create Post'; ?>
                                </button>
                                <?php if ($editPost): ?>
                                    <a href="post-management.php" class="btn btn-secondary">Cancel</a>
                                <?php endif; ?>
                            </form>
                        </div>
                    </div>

                    <!-- Posts Table -->
                    <div class="card mt-5">
                        <div class="card-body">
                            <h2>Existing Posts</h2>
                            <div class="table-responsive">
                                <table class="table table-bordered mt-3">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Title</th>
                                            <th>Slug</th>
                                            <th>Category</th>
                                            <th>Image</th>
                                            <th>Author</th>
                                            <th>Status</th>
                                            <th>Created</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($posts as $post): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($post['id']); ?></td>
                                                <td><?php echo htmlspecialchars($post['title']); ?></td>
                                                <td><?php echo htmlspecialchars($post['slug']); ?></td>
                                                <td><?php echo htmlspecialchars($post['category']); ?></td>
                                                <td>
                                                    <?php if (!empty($post['image'])): ?>
                                                        <img src="<?php echo htmlspecialchars($post['image']); ?>" 
                                                            alt="<?php echo htmlspecialchars($post['title']); ?>" 
                                                            class="img-thumbnail"
                                                            style="width: 80px; height: 60px; object-fit: cover;">
                                                    <?php else: ?>
                                                        No Image
                                                    <?php endif; ?>
                                                </td>
                                                <td><?php echo htmlspecialchars($post['author']); ?></td>
                                                <td>
                                                    <span class="badge <?php echo $post['status'] === 'published' ? 'bg-success' : 'bg-warning'; ?>">
                                                        <?php echo ucfirst(htmlspecialchars($post['status'])); ?>
                                                    </span>
                                                </td>
                                                <td><?php echo date('Y-m-d H:i', strtotime($post['created_at'])); ?></td>
                                                <td>
                                                    <a href="post-management.php?edit=<?php echo $post['id']; ?>" 
                                                    class="btn btn-warning btn-sm">Edit</a>
                                                    <a href="post-management.php?delete=<?php echo $post['id']; ?>" 
                                                    class="btn btn-danger btn-sm"
                                                    onclick="return confirm('Are you sure you want to delete this post?');">Delete</a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            <?php if (empty($posts)): ?>
                                <div class="alert alert-info mt-3">No posts found.</div>
                            <?php endif; ?>
                        </div>
</div>
<?php
// Function to create a new comment
function createComment($pdo, $postId, $name, $comment) {
    $stmt = $pdo->prepare("INSERT INTO comments (post_id, name, comment) VALUES (?, ?, ?)");
    return $stmt->execute([$postId, $name, $comment]);
}

// Corrected function to read comments with pagination
function readComments($pdo, $postId, $offset, $limit) {
    $stmt = $pdo->prepare("SELECT * FROM comments WHERE post_id = ? ORDER BY created_at DESC LIMIT $offset, $limit");
    $stmt->execute([$postId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Function to count total comments for a specific post
function countComments($pdo, $postId) {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM comments WHERE post_id = ?");
    $stmt->execute([$postId]);
    return $stmt->fetchColumn();
}

// Function to delete a specific comment by its ID
function deleteComment($pdo, $commentId) {
    $stmt = $pdo->prepare("DELETE FROM comments WHERE id = ?");
    return $stmt->execute([$commentId]);
}

// Assuming you have a PDO connection $pdo
$postId = 10; // Example post ID

// Handle comment deletion
if (isset($_GET['delete_comment'])) {
    $commentId = $_GET['delete_comment'];
    if (deleteComment($pdo, $commentId)) {
        echo "<div class='alert alert-success'>Comment deleted successfully!</div>";
    } else {
        echo "<div class='alert alert-danger'>Failed to delete comment.</div>";
    }
}

// Pagination settings
$limit = 5; // Number of comments per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Current page
$offset = ($page - 1) * $limit; // Calculate offset

// Read comments with pagination
$comments = readComments($pdo, $postId, $offset, $limit);

// Count total comments
$totalComments = countComments($pdo, $postId);
$totalPages = ceil($totalComments / $limit); // Total pages
?>

<!-- Comments Section -->
<div class="comments-section">
    <h3>Comments for Post ID: <?php echo htmlspecialchars($postId); ?></h3>
    <?php if (!empty($comments)): ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Post ID</th>
                    <th>Name</th>
                    <th>Comment</th>
                    <th>Created At</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($comments as $comment): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($comment['post_id']); ?></td>
                        <td><?php echo htmlspecialchars($comment['name']); ?></td>
                        <td><?php echo nl2br(htmlspecialchars($comment['comment'])); ?></td>
                        <td><?php echo date('Y-m-d H:i', strtotime($comment['created_at'])); ?></td>
                        <td>
                            <a href="?delete_comment=<?php echo $comment['id']; ?>" 
                               class="btn btn-danger btn-sm" 
                               onclick="return confirm('Are you sure you want to delete this comment?');">
                               Delete
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <!-- Pagination Controls -->
        <nav aria-label="Page navigation">
            <ul class="pagination">
                <?php if ($page > 1): ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?php echo $page - 1; ?>" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>
                <?php endif; ?>
                
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <li class="page-item <?php echo $i === $page ? 'active' : ''; ?>">
                        <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                    </li>
                <?php endfor; ?>
                
                <?php if ($page < $totalPages): ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?php echo $page + 1; ?>" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>
    <?php else: ?>
        <p class="text-muted">No comments yet.</p>
    <?php endif; ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add hover effect to table rows
    const rows = document.querySelectorAll('tbody tr');
    rows.forEach(row => {
        row.addEventListener('mouseover', function() {
            this.style.backgroundColor = '#f8f9fa';
        });
        row.addEventListener('mouseout', function() {
            this.style.backgroundColor = '';
        });
    });
});
</script>

<?php include 'layouts/footer.php'; ?>
