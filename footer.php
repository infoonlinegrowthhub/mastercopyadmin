<?php
// Fetch footer content from footer_content table
$query = $pdo->prepare("SELECT * FROM footer_content LIMIT 1");
$query->execute();
$footer = $query->fetch(PDO::FETCH_ASSOC);

// Fetch short links
$query = $pdo->prepare("SELECT * FROM footer_links WHERE link_type='internal' ORDER BY display_order ASC");
$query->execute();
$shortLinks = $query->fetchAll(PDO::FETCH_ASSOC);

// Fetch help links
$query = $pdo->prepare("SELECT * FROM help_links ORDER BY display_order ASC");
$query->execute();
$helpLinks = $query->fetchAll(PDO::FETCH_ASSOC);

// Fetch the WhatsApp number
$stmt = $pdo->prepare("SELECT whatsapp_number FROM whatsapp_settings WHERE id = 1");
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);
$whatsapp_number = $result['whatsapp_number'];
?>

<!-- Footer Start -->
<div class="container-fluid footer bg-dark wow fadeIn" data-wow-delay=".3s">
    <div class="container pt-5 pb-4">
        <div class="row g-5">
            <!-- Company Information -->
            <div class="col-lg-3 col-md-6 col-12">
                <a href="<?php echo BASE_URL; ?>">
                    <h1 class="text-white fw-bold"><?php echo $footer['company_name']; ?></h1>
                </a>
                <p class="mt-4 text-light"><?php echo $footer['description']; ?></p>
                <!-- Social Media Links -->
                <div class="d-flex hightech-link">
                    <a href="#" class="btn btn-square btn-light rounded-circle me-2">
                        <i class="fab fa-facebook-f text-primary"></i>
                    </a>
                    <a href="#" class="btn btn-square btn-light rounded-circle me-2">
                        <i class="fab fa-twitter text-primary"></i>
                    </a>
                    <a href="#" class="btn btn-square btn-light rounded-circle me-2">
                        <i class="fab fa-instagram text-primary"></i>
                    </a>
                    <a href="#" class="btn btn-square btn-light rounded-circle">
                        <i class="fab fa-linkedin-in text-primary"></i>
                    </a>
                </div>
            </div>

            <!-- Short Links -->
            <div class="col-lg-3 col-md-6 col-6">
                <h3 class="text-secondary">Short Links</h3>
                <div class="mt-4 d-flex flex-column">
                    <?php foreach ($shortLinks as $link): ?>
                        <a href="<?php echo BASE_URL . $link['link_url']; ?>" class="mb-2 text-white">
                            <i class="fas fa-angle-right text-secondary me-2"></i><?php echo $link['link_text']; ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Help Links -->
            <div class="col-lg-3 col-md-6 col-6">
                <h3 class="text-secondary">Help Links</h3>
                <div class="mt-4 d-flex flex-column">
                    <?php foreach ($helpLinks as $helpLink): ?>
                        <a href="<?php echo BASE_URL . $helpLink['link_url']; ?>" class="mb-2 text-white">
                            <i class="fas fa-angle-right text-secondary me-2"></i><?php echo $helpLink['link_text']; ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Contact Information -->
            <div class="col-lg-3 col-md-6 col-12">
                <h3 class="text-secondary">Contact Us</h3>
                <div class="mt-4 d-flex flex-column">
                    <a href="#" class="pb-3 text-light border-bottom border-primary">
                        <i class="fas fa-map-marker-alt text-secondary me-2"></i> <?php echo $footer['address']; ?>
                    </a>
                    <a href="#" class="py-3 text-light border-bottom border-primary">
                        <i class="fas fa-phone-alt text-secondary me-2"></i> <?php echo $footer['phone']; ?>
                    </a>
                    <a href="#" class="py-3 text-light border-bottom border-primary">
                        <i class="fas fa-envelope text-secondary me-2"></i> <?php echo $footer['email']; ?>
                    </a>
                </div>
            </div>
        </div>

        <!-- Copyright Section -->
        <hr class="text-light mt-5 mb-4">
        <div class="row">
            <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                <span class="text-light">
                    <i class="fas fa-copyright text-secondary me-2"></i>
                    <?php echo $footer['company_name']; ?>, All rights reserved.
                </span>
            </div>
            <div class="col-md-6 text-center text-md-end">
                <span class="text-light">Designed by 
                    <a href="<?php echo $footer['designer_url']; ?>" class="text-secondary"><?php echo $footer['designer_name']; ?></a>
                </span>
            </div>
        </div>
    </div>
</div>
<!-- Footer End -->

<!-- WhatsApp Floating Button -->
<a href="#" class="whatsapp-btn" id="whatsappButton">
    <i class="fab fa-whatsapp"></i>
</a>

<!-- Back to Top -->
<a href="#" class="btn btn-secondary btn-square rounded-circle back-to-top"><i class="fa fa-arrow-up text-white"></i></a>

<!-- JavaScript Libraries -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?php echo BASE_URL; ?>lib/wow/wow.min.js"></script>
<script src="<?php echo BASE_URL; ?>lib/easing/easing.min.js"></script>
<script src="<?php echo BASE_URL; ?>lib/waypoints/waypoints.min.js"></script>
<script src="<?php echo BASE_URL; ?>lib/owlcarousel/owl.carousel.min.js"></script>

<!-- Template Javascript -->
<script src="<?php echo BASE_URL; ?>js/main.js"></script>

<!-- WhatsApp Button Script -->
<script>
    document.getElementById('whatsappButton').addEventListener('click', function(e) {
        e.preventDefault();
        var phoneNumber = '<?php echo $whatsapp_number; ?>'; // Fetched from database
        var message = 'Hello, I have a question!'; // Optional: Pre-filled message
        var whatsappUrl = 'https://wa.me/' + phoneNumber + '?text=' + encodeURIComponent(message);
        window.open(whatsappUrl, '_blank');
    });
</script>

</body>
</html>