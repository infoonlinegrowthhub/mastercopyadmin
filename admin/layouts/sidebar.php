<div class="main-sidebar">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="<?php echo ADMIN_URL; ?>dashboard.php">Admin Panel</a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            <a href="<?php echo ADMIN_URL; ?>dashboard.php"></a>
        </div>

        <ul class="sidebar-menu">
            <!-- Dashboard -->
            <li class="<?php if ($cur_page == 'dashboard.php') { echo 'active'; } ?>">
                <a class="nav-link" href="<?php echo ADMIN_URL; ?>dashboard.php"><i class="fas fa-hand-point-right"></i> <span>Dashboard</span></a>
            </li>

            <!-- User Management -->
            <li class="nav-item dropdown <?php if (in_array($cur_page, ['user-management.php'])) { echo 'active'; } ?>">
                <a href="#" class="nav-link has-dropdown">
                    <i class="fas fa-users"></i> <span>User Management</span>
                </a>
                <ul class="dropdown-menu">
                    <li class="<?php if ($cur_page == 'user-management.php') { echo 'active'; } ?>">
                        <a class="nav-link" href="<?php echo ADMIN_URL; ?>user-management.php">User Management</a>
                    </li>
                </ul>
            </li>

            <!-- Page Management -->
            <li class="nav-item dropdown <?php if (in_array($cur_page, ['carousel-management.php', 'fact-management.php', 'about-management.php', 'services-management.php',
			'project-management.php', 'post-management.php', 'products-management.php', 'testimonial-management.php', 'contact-management.php'])) { echo 'active'; } ?>">
                <a href="#" class="nav-link has-dropdown">
                    <i class="fas fa-sticky-note"></i> <span>Content Management</span>
                </a>
                <ul class="dropdown-menu">
                    <!-- <li class="<?php if ($cur_page == 'carousel-management.php') { echo 'active'; } ?>">
                        <a class="nav-link" href="<?php echo ADMIN_URL; ?>carousel-management.php">Carousel Management</a>
                    </li>
                    <li class="<?php if ($cur_page == 'fact-management.php') { echo 'active'; } ?>">
                        <a class="nav-link" href="<?php echo ADMIN_URL; ?>fact-management.php">Fact Management</a>
                    </li>
                    <li class="<?php if ($cur_page == 'about-management.php') { echo 'active'; } ?>">
                        <a class="nav-link" href="<?php echo ADMIN_URL; ?>about-management.php">About Management</a>
                    </li>
                    <li class="<?php if ($cur_page == 'services-management.php ') { echo 'active'; } ?>">
                        <a class="nav-link" href="<?php echo ADMIN_URL; ?>services-management.php ">Services Management</a>
                    </li>
                    <li class="<?php if ($cur_page == 'project-management.php') { echo 'active'; } ?>">
                        <a class="nav-link" href="<?php echo ADMIN_URL; ?>project-management.php">Project Management</a> -->
                    <!-- </li>
                    <li class="<?php if ($cur_page == 'team-management.php') { echo 'active'; } ?>">
                        <a class="nav-link" href="<?php echo ADMIN_URL; ?>team-management.php">Team Management</a>
                    </li>
                    <li class="<?php if ($cur_page == 'post-management.php') { echo 'active'; } ?>">
                        <a class="nav-link" href="<?php echo ADMIN_URL; ?>post-management.php">Blog Management</a>
                    </li>
                    <li class="<?php if ($cur_page == 'post-management.php') { echo 'active'; } ?>">
                        <a class="nav-link" href="<?php echo ADMIN_URL; ?>products-management.php">Products Management</a>
                    </li>
					<li class="<?php if ($cur_page == 'testimonial-management.php') { echo 'active'; } ?>">
                        <a class="nav-link" href="<?php echo ADMIN_URL; ?>testimonial-management.php">Testimonial Management</a>
                    </li> -->
                    <li class="<?php if ($cur_page == 'email-received.php') { echo 'active'; } ?>">
                        <a class="nav-link" href="<?php echo ADMIN_URL; ?>email-received.php">Email Received</a>
                    </li>
                    <li class="<?php if ($cur_page == 'topbar-management.php') { echo 'active'; } ?>">
                        <a class="nav-link" href="<?php echo ADMIN_URL; ?>topbar-management.php">Update Topbar</a>
                    </li>
                    <li class="<?php if ($cur_page == 'footer-management.php') { echo 'active'; } ?>">
                        <a class="nav-link" href="<?php echo ADMIN_URL; ?>footer-management.php">Update Footer</a>
                    </li>
                    <!-- <li class="<?php if ($cur_page == 'contact-management.php') { echo 'active'; } ?>">
                        <a class="nav-link" href="<?php echo ADMIN_URL; ?>contact-management.php">Update Contact Info</a>
                    </li> -->
                </ul>
            </li>
            <!-- Settings & Invoices -->
            <li class="nav-item dropdown <?php if (in_array($cur_page, ['setting.php', 'invoice.php', 'view-invoices.php', 'whatsapp_settings.php', 'cashfree-config-management.php', 'customer-management.php'])) { echo 'active'; } ?>">
                <a href="#" class="nav-link has-dropdown">
                    <i class="fas fa-cog"></i> <span>Settings & Invoices</span>
                </a>
                <ul class="dropdown-menu">
                    <li class="<?php if ($cur_page == 'setting.php') { echo 'active'; } ?>">
                        <a class="nav-link" href="<?php echo ADMIN_URL; ?>setting.php">Settings</a>
                    </li>
                    <li class="<?php if ($cur_page == 'invoice.php') { echo 'active'; } ?>">
                    <a class="nav-link" href="<?php echo ADMIN_URL; ?>invoice.php">Generate Invoice</a>
                    </li>
                    <li class="<?php if ($cur_page == 'view-invoices.php') { echo 'active'; } ?>">
                    <a class="nav-link" href="<?php echo ADMIN_URL; ?>view-invoices.php">View Invoices</a>
                    </li>
                    <!-- <li class="<?php if ($cur_page == 'cashfree-config-management.php') { echo 'active'; } ?>">
                        <a class="nav-link" href="<?php echo ADMIN_URL; ?>cashfree-config-management.php">Cashfree Config Management</a>
                    </li> -->
                    <!-- <li class="<?php if ($cur_page == 'customer-management.php') { echo 'active'; } ?>">
                        <a class="nav-link" href="<?php echo ADMIN_URL; ?>customer-management.php">Customer Management Php</a>
                    </li> -->
                    <li class="<?php if ($cur_page == 'whatsapp_settings.php') { echo 'active'; } ?>">
                        <a class="nav-link" href="<?php echo ADMIN_URL; ?>whatsapp_settings.php">WhatsApp Settings</a>
                    </li>
                </ul>
            </li>
            <!-- Logout -->  
            <li>
                <a class="nav-link" href="logout.php"><i class="fas fa-sign-out-alt"></i> <span>Logout</span></a>
            </li>
            </ul>
    </aside>
</div>
