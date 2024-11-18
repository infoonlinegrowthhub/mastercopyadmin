<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require 'config/config.php';

// Avoid defining BASE_URL if it's already defined
if (!defined("BASE_URL")) {
    define("BASE_URL", "https://onlinegrowthhub.in/");
}

// Set the content type to XML
header('Content-Type: application/xml; charset=utf-8');

// Start the XML output
echo '<?xml version="1.0" encoding="UTF-8"?>';
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" 
              xmlns:image="http://www.sitemaps.org/schemas/sitemap-image/1.1">';

// Add the homepage
echo '<url>';
echo '<loc>' . htmlspecialchars(BASE_URL) . '</loc>';
echo '<changefreq>daily</changefreq>';
echo '<priority>1.0</priority>';
echo '</url>';

// Static pages
$staticPages = [
    'about' => ['changefreq' => 'monthly', 'priority' => '0.6'],
    'contact' => ['changefreq' => 'monthly', 'priority' => '0.6'],
    'faq' => ['changefreq' => 'monthly', 'priority' => '0.5'],
    'terms' => ['changefreq' => 'monthly', 'priority' => '0.5'],
    'privacy' => ['changefreq' => 'monthly', 'priority' => '0.5'],
];

// Loop through static pages and add them to the sitemap
foreach ($staticPages as $slug => $meta) {
    echo '<url>';
    echo '<loc>' . htmlspecialchars(BASE_URL . $slug) . '</loc>';
    echo '<changefreq>' . $meta['changefreq'] . '</changefreq>';
    echo '<priority>' . $meta['priority'] . '</priority>';
    echo '</url>';
}

// Connect to the database using PDO
try {
    $pdo = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch all published posts
    $stmt = $pdo->query("SELECT slug FROM posts WHERE status = 'published'");
    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Loop through each post and add it to the sitemap
    foreach ($posts as $post) {
        echo '<url>';
        echo '<loc>' . htmlspecialchars(BASE_URL . 'web-design/' . $post['slug']) . '</loc>';
        echo '<changefreq>weekly</changefreq>';
        echo '<priority>0.7</priority>';
        echo '</url>';
    }

    // Fetch all active products
    $stmt = $pdo->query("SELECT id FROM products WHERE status = 1");
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Loop through each product and add it to the sitemap
    foreach ($products as $product) {
        echo '<url>';
        echo '<loc>' . htmlspecialchars(BASE_URL . 'products/' . $product['id']) . '</loc>'; // Adjust as necessary for your product URLs
        echo '<changefreq>monthly</changefreq>';
        echo '<priority>0.8</priority>';
        echo '</url>';
    }

    // Add other relevant pages here (e.g., services)
    $additionalPages = [
        'services' => ['changefreq' => 'monthly', 'priority' => '0.6'],
        'projects' => ['changefreq' => 'monthly', 'priority' => '0.5'],
    ];

    foreach ($additionalPages as $slug => $meta) {
        echo '<url>';
        echo '<loc>' . htmlspecialchars(BASE_URL . $slug) . '</loc>';
        echo '<changefreq>' . $meta['changefreq'] . '</changefreq>';
        echo '<priority>' . $meta['priority'] . '</priority>';
        echo '</url>';
    }

} catch (PDOException $e) {
    echo 'Database error: ' . $e->getMessage();
}

echo '</urlset>';
?>
