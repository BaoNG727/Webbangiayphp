<?php
/**
 * Hybrid Router - Nike Shoe Store
 * Combines direct routing with MVC pattern for reliability
 */

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Load core classes
require_once __DIR__ . '/app/Core/Database.php';
require_once __DIR__ . '/app/Core/Model.php';
require_once __DIR__ . '/app/Core/Controller.php';
require_once __DIR__ . '/app/Core/Security.php';

// Autoload controllers and models
spl_autoload_register(function ($class) {
    $paths = [
        __DIR__ . '/app/Controllers/',
        __DIR__ . '/app/Models/'
    ];
    
    foreach ($paths as $path) {
        $file = $path . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            break;
        }
    }
});

// Get request path
$request = $_SERVER['REQUEST_URI'];
$path = parse_url($request, PHP_URL_PATH);

// Remove base path
$basePath = '/Webgiay';
if (strpos($path, $basePath) === 0) {
    $path = substr($path, strlen($basePath));
}

// Normalize path
if (empty($path) || $path === '') {
    $path = '/';
} elseif ($path[0] !== '/') {
    $path = '/' . $path;
}

// Remove trailing slash except for root
if ($path !== '/' && substr($path, -1) === '/') {
    $path = rtrim($path, '/');
}

// Simple routing logic
try {
    $method = $_SERVER['REQUEST_METHOD'];
    
    // Route matching
    switch (true) {
        // Home routes
        case ($path === '/' || $path === '/home'):
            $controller = new HomeController();
            $controller->index();
            break;
            
        // Product routes
        case ($path === '/products'):
            $controller = new ProductController();
            $controller->index();
            break;
            
        case (preg_match('#^/product/(\d+)$#', $path, $matches)):
            $controller = new ProductController();
            $controller->show($matches[1]);
            break;
            
        case ($path === '/product/add-to-cart' && $method === 'POST'):
            $controller = new ProductController();
            $controller->addToCart();
            break;
            
        // Auth routes
        case ($path === '/login'):
            $controller = new AuthController();
            $controller->login();
            break;
            
        case ($path === '/register'):
            $controller = new AuthController();
            $controller->register();
            break;
            
        case ($path === '/logout'):
            $controller = new AuthController();
            $controller->logout();
            break;
            
        // Cart routes
        case ($path === '/cart'):
            $controller = new CartController();
            $controller->index();
            break;
            
        case ($path === '/cart/update' && $method === 'POST'):
            $controller = new CartController();
            $controller->update();
            break;
            
        case ($path === '/cart/remove' && $method === 'POST'):
            $controller = new CartController();
            $controller->remove();
            break;
            
        case ($path === '/cart/clear' && $method === 'POST'):
            $controller = new CartController();
            $controller->clear();
            break;
            
        // Checkout routes
        case ($path === '/checkout'):
            $controller = new CheckoutController();
            $controller->index();
            break;
            
        case ($path === '/checkout' && $method === 'POST'):
            $controller = new CheckoutController();
            $controller->process();
            break;
            
        case ($path === '/checkout/success'):
            $controller = new CheckoutController();
            $controller->success();
            break;
            
        // Order routes
        case ($path === '/orders'):
            $controller = new OrderController();
            $controller->index();
            break;
            
        case (preg_match('#^/orders/(\d+)$#', $path, $matches)):
            $controller = new OrderController();
            $controller->show($matches[1]);
            break;
            
        case (preg_match('#^/orders/(\d+)/status$#', $path, $matches) && $method === 'POST'):
            $controller = new OrderController();
            $controller->updateStatus($matches[1]);
            break;
            
        // Static pages
        case ($path === '/about'):
            $controller = new PageController();
            $controller->about();
            break;
            
        case ($path === '/contact'):
            $controller = new PageController();
            $controller->contact();
            break;
            
        case ($path === '/terms'):
            $controller = new PageController();
            $controller->terms();
            break;
            
        case ($path === '/privacy'):
            $controller = new PageController();
            $controller->privacy();
            break;
            
        // Default - 404
        default:
            http_response_code(404);
            $title = 'Page Not Found - Nike Shoe Store';
            include __DIR__ . '/app/Views/layouts/header.php';
            include __DIR__ . '/app/Views/errors/404.php';
            include __DIR__ . '/app/Views/layouts/footer.php';
            break;
    }
    
} catch (Exception $e) {
    // Error handling
    http_response_code(500);
    $title = 'Server Error - Nike Shoe Store';
    $error_message = $e->getMessage();
    
    include __DIR__ . '/app/Views/layouts/header.php';
    include __DIR__ . '/app/Views/errors/500.php';
    include __DIR__ . '/app/Views/layouts/footer.php';
}
?>
