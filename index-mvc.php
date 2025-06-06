<?php

// Set secure headers first
require_once __DIR__ . '/app/Core/Security.php';
Security::setSecureHeaders();

// Autoload core classes
spl_autoload_register(function ($class) {
    $paths = [
        __DIR__ . '/app/Core/',
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

// Start session with secure settings
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', Security::isHTTPS() ? 1 : 0);
ini_set('session.use_strict_mode', 1);
session_start();

// Get the URL path
$request = $_SERVER['REQUEST_URI'];
$path = parse_url($request, PHP_URL_PATH);

// Remove the base path (adjust this based on your setup)
$basePath = '/Webgiay';
if (strpos($path, $basePath) === 0) {
    $path = substr($path, strlen($basePath));
}

// Ensure path starts with / and handle empty path
if (empty($path) || $path === '') {
    $path = '/';
} elseif ($path[0] !== '/') {
    $path = '/' . $path;
}

// Initialize router
$router = new Router();

// Define routes
$router->get('/', 'HomeController', 'index');
$router->get('/home', 'HomeController', 'index');

// Product routes
$router->get('/products', 'ProductController', 'index');
$router->get('/product/{id}', 'ProductController', 'show');
$router->post('/product/add-to-cart', 'ProductController', 'addToCart');

// Auth routes
$router->get('/login', 'AuthController', 'login');
$router->post('/login', 'AuthController', 'login');
$router->get('/register', 'AuthController', 'register');
$router->post('/register', 'AuthController', 'register');
$router->get('/logout', 'AuthController', 'logout');

// Cart routes
$router->get('/cart', 'CartController', 'index');
$router->post('/cart/update', 'CartController', 'update');
$router->post('/cart/remove', 'CartController', 'remove');
$router->post('/cart/clear', 'CartController', 'clear');

// Checkout routes
$router->get('/checkout', 'CheckoutController', 'index');
$router->post('/checkout', 'CheckoutController', 'process');
$router->get('/checkout/success', 'CheckoutController', 'success');

// Order routes
$router->get('/orders', 'OrderController', 'index');
$router->get('/orders/{id}', 'OrderController', 'show');
$router->post('/orders/{id}/status', 'OrderController', 'updateStatus');

// Static pages
$router->get('/about', 'PageController', 'about');
$router->get('/contact', 'PageController', 'contact');
$router->post('/contact', 'PageController', 'contact');
$router->get('/terms', 'PageController', 'terms');
$router->get('/privacy', 'PageController', 'privacy');

// Debug: Log the path being dispatched
error_log("Dispatching path: " . $path);

// Dispatch the request
try {
    $router->dispatch($path);
} catch (Exception $e) {
    // Log the error for debugging
    error_log("Router Exception: " . $e->getMessage());
    error_log("Stack trace: " . $e->getTraceAsString());
    
    // Handle errors
    http_response_code(500);
    
    // Include error page
    $title = 'Server Error - Nike Shoe Store';
    include __DIR__ . '/app/Views/layouts/header.php';
    include __DIR__ . '/app/Views/errors/500.php';
    include __DIR__ . '/app/Views/layouts/footer.php';
}
