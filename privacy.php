<?php
session_start();
require 'config/config.php'; // Include your database configuration
include 'header.php'; // Include the header
include 'navbar.php'; // Include the navigation bar
?>

<!-- Page Header Start -->
<div class="container-fluid page-header py-5">
    <div class="container text-center py-5">
        <h1 class="display-2 text-white mb-4 animated slideInDown">Privacy Policy</h1>
        <nav aria-label="breadcrumb animated slideInDown">
            <ol class="breadcrumb justify-content-center mb-0">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item"><a href="#">Pages</a></li>
                <li class="breadcrumb-item" aria-current="page">Privacy Policy</li>
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
<!-- Privacy Policy Start -->
<div class="container py-5">
    <h2 class="mb-4">Your Privacy Matters to Us</h2>
    <p>
        At Online Growth Hub, we are committed to protecting your privacy. This Privacy Policy explains how we collect, use, disclose, and safeguard your information when you visit our website or engage with our services.
    </p>
    
    <h3 class="mt-4">1. Information We Collect</h3>
    <p>We may collect information about you in various ways, including:</p>
    <ul>
        <li><strong>Personal Information:</strong> Name, email address, phone number, and any other information you provide when you register or contact us.</li>
        <li><strong>Usage Data:</strong> Information on how you interact with our website, including IP address, browser type, pages visited, and time spent on the site.</li>
        <li><strong>Cookies and Tracking Technologies:</strong> We use cookies and similar tracking technologies to monitor activity on our service and store certain information.</li>
    </ul>

    <h3 class="mt-4">2. How We Use Your Information</h3>
    <p>We use the information we collect for various purposes, including:</p>
    <ul>
        <li>To provide and maintain our services.</li>
        <li>To notify you about changes to our services.</li>
        <li>To allow you to participate in interactive features of our service when you choose to do so.</li>
        <li>To provide customer support.</li>
        <li>To gather analysis or valuable information so that we can improve our services.</li>
        <li>To monitor the usage of our services.</li>
        <li>To detect, prevent, and address technical issues.</li>
        <li>To communicate with you, including for marketing and promotional purposes.</li>
    </ul>

    <h3 class="mt-4">3. Disclosure of Your Information</h3>
    <p>We may share your information in the following situations:</p>
    <ul>
        <li><strong>With Service Providers:</strong> We may share your information with third-party service providers to perform services on our behalf.</li>
        <li><strong>For Business Transfers:</strong> If we are involved in a merger, acquisition, or asset sale, your personal information may be transferred.</li>
        <li><strong>With Your Consent:</strong> We may disclose your personal information for any other purpose with your consent.</li>
    </ul>

    <h3 class="mt-4">4. Security of Your Information</h3>
    <p>The security of your personal information is important to us, but remember that no method of transmission over the Internet or method of electronic storage is 100% secure. While we strive to use commercially acceptable means to protect your personal information, we cannot guarantee its absolute security.</p>

    <h3 class="mt-4">5. Your Rights</h3>
    <p>Depending on your location, you may have the following rights regarding your personal information:</p>
    <ul>
        <li>The right to access – You have the right to request copies of your personal information.</li>
        <li>The right to rectification – You have the right to request that we correct any information you believe is inaccurate or incomplete.</li>
        <li>The right to erasure – You have the right to request that we erase your personal information, under certain conditions.</li>
        <li>The right to restrict processing – You have the right to request that we restrict the processing of your personal information, under certain conditions.</li>
        <li>The right to object to processing – You have the right to object to our processing of your personal information, under certain conditions.</li>
        <li>The right to data portability – You have the right to request that we transfer the data we have collected to another organization, or directly to you, under certain conditions.</li>
    </ul>

    <h3 class="mt-4">6. Changes to This Privacy Policy</h3>
    <p>We may update our Privacy Policy from time to time. We will notify you of any changes by posting the new Privacy Policy on this page. You are advised to review this Privacy Policy periodically for any changes. Changes to this Privacy Policy are effective when they are posted on this page.</p>

    <h3 class="mt-4">7. Contact Us</h3>
    <p>If you have any questions about this Privacy Policy, please contact us:</p>
    <ul>
        <li>Email: <a href="mailto:onlinegrowthhub@gmail.com">onlinegrowthhub@gmail.com</a></li>
        <li>Phone: 📞 9032666855</li>
        <li>Website: <a href="http://onlinegrowthhub.in">onlinegrowthhub.in</a></li>
    </ul>
</div>
<!-- Privacy Policy End -->

<?php
include 'footer.php'; // Include the footer
?>
