<div class="navbar-bg"></div>
<nav class="navbar navbar-expand-lg main-navbar">
    <a href="#" data-toggle="sidebar" class="nav-link nav-link-lg d-lg-none">
        <i class="fas fa-bars"></i>
    </a>
    <ul class="navbar-nav ms-auto">
        <!-- Front End link aligned to the right -->
        <li class="nav-item">
            <a href="<?php echo BASE_URL; ?>" target="_blank" class="btn btn-warning btn-sm me-2">Front End</a>
        </li>

        <!-- Admin logged in status aligned to the right -->
        <li class="nav-item">
            <?php if (isset($_SESSION['admin']['full_name'])): ?>
                <span class="nav-link">Logged in as: <?php echo htmlspecialchars($_SESSION['admin']['full_name']); ?></span>
            <?php else: ?>
                <span class="nav-link">No admin logged in.</span>
            <?php endif; ?>
        </li>

        <!-- Admin Profile Dropdown aligned to the right -->
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <?php if (empty($_SESSION['admin']['photo'])): ?>
                    <img alt="image" src="<?php echo BASE_URL; ?>img/default.png" class="rounded-circle-custom">
                <?php else: ?>
                    <img alt="image" src="<?php echo BASE_URL; ?>img/<?php echo htmlspecialchars($_SESSION['admin']['photo']); ?>" class="rounded-circle-custom">
                <?php endif; ?>
            </a>

            <!-- Dropdown Menu -->
            <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="<?php echo ADMIN_URL; ?>profile.php"><i class="far fa-user"></i> Edit Profile</a></li>
                <li><a class="dropdown-item" href="<?php echo ADMIN_URL; ?>logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </li>
    </ul>
</nav>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle sidebar on mobile
    const sidebarToggle = document.querySelector('[data-toggle="sidebar"]');
    const mainSidebar = document.querySelector('.main-sidebar');

    if (sidebarToggle && mainSidebar) {
        sidebarToggle.addEventListener('click', function(e) {
            e.preventDefault();
            mainSidebar.classList.toggle('active');
        });
    }

    // Close sidebar when clicking outside of it on mobile
    document.addEventListener('click', function(e) {
        if (window.innerWidth <= 991 && !e.target.closest('.main-sidebar') && !e.target.closest('[data-toggle="sidebar"]')) {
            if (mainSidebar && mainSidebar.classList.contains('active')) {
                mainSidebar.classList.remove('active');
            }
        }
    });
});
</script>
