<head>
    <meta charset="utf-8">
    <title>Online Growth Hub - IT Solutions Website</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <?php
    // Fetch Current Settings
    $statement = $pdo->prepare("SELECT site_icon, keywords, description FROM settings WHERE id = 1");
    $statement->execute();
    $settings = $statement->fetch(PDO::FETCH_ASSOC);
    ?>
    
    <!-- Site Icon -->
    <link rel="icon" href="<?php echo BASE_URL . 'img/' . htmlspecialchars($settings['site_icon']); ?>" type="image/png">

    <!-- Dynamic Meta Tags -->
    <meta content="<?php echo htmlspecialchars($settings['keywords']); ?>" name="keywords">
    <meta content="<?php echo htmlspecialchars($settings['description']); ?>" name="description">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Saira:wght@500;600;700&display=swap" rel="stylesheet">
    
    <!-- Cashfree SDK -->
    <script src="https://sdk.cashfree.com/js/v3/cashfree.js"></script>

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="<?php echo BASE_URL; ?>lib/animate/animate.min.css" rel="stylesheet">
    <link href="<?php echo BASE_URL; ?>lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="<?php echo BASE_URL; ?>css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="<?php echo BASE_URL; ?>css/style.css" rel="stylesheet">

    <!-- Meta Pixel Code -->
    <script>
    !function(f,b,e,v,n,t,s)
    {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
    n.callMethod.apply(n,arguments):n.queue.push(arguments)}; 
    if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0'; 
    n.queue=[];t=b.createElement(e);t.async=!0; 
    t.src=v;s=b.getElementsByTagName(e)[0]; 
    s.parentNode.insertBefore(t,s)}(window, document,'script', 
    'https://connect.facebook.net/en_US/fbevents.js'); 
    fbq('init', '786780305534587'); 
    fbq('track', 'PageView'); 
    </script>
    <noscript><img height="1" width="1" style="display:none"
    src="https://www.facebook.com/tr?id=786780305534587&ev=PageView&noscript=1"
/></noscript>
    <!-- End Meta Pixel Code -->

    <!-- Google Tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-P7LK1ECVTF"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', 'G-P7LK1ECVTF');
    </script>
    <!-- End Google Tag -->
</head>
