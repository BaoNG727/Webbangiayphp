<?php

require_once __DIR__ . '/../Core/Controller.php';
require_once __DIR__ . '/../Core/Database.php';

class ProductController extends Controller
{    public function index()
    {
        $productModel = $this->model('Product');
        
        // Get filters from request
        $filters = [
            'category' => $this->input('category'),
            'sale' => $this->input('sale'),
            'sort' => $this->input('sort', 'newest'),
            'search' => $this->input('search')
        ];

        // Pagination
        $page = max(1, (int)$this->input('page', 1));
        $perPage = 12;
        
        // Get products and total count
        $products = $productModel->search($filters, $page, $perPage);
        $totalProducts = $productModel->searchCount($filters);
        $totalPages = ceil($totalProducts / $perPage);

        // Build query string for pagination links
        $queryParams = array_filter($filters, function($value) {
            return $value !== null && $value !== '';
        });
        $queryString = http_build_query($queryParams);

        $data = [
            'title' => 'Products - Nike Shoe Store',
            'products' => $products,
            'categories' => $productModel->getCategories(),
            'current_filters' => $filters,
            'product_count' => $totalProducts,
            'pagination' => [
                'current_page' => $page,
                'total_pages' => $totalPages,
                'per_page' => $perPage,
                'total_items' => $totalProducts,
                'has_prev' => $page > 1,
                'has_next' => $page < $totalPages,
                'prev_page' => $page - 1,
                'next_page' => $page + 1,
                'query_string' => $queryString
            ]
        ];

        $this->view('layouts/header', $data);
        $this->view('products/index', $data);
        $this->view('layouts/footer');
    }

    public function show($id)
    {
        $productModel = $this->model('Product');
        $product = $productModel->find($id);

        if (!$product) {
            http_response_code(404);
            $this->view('layouts/header', ['title' => 'Product Not Found']);
            $this->view('errors/404');
            $this->view('layouts/footer');
            return;
        }

        $data = [
            'title' => $product['name'] . ' - Nike Shoe Store',
            'product' => $product,
            'related_products' => $productModel->getByCategory($product['category'])
        ];

        $this->view('layouts/header', $data);
        $this->view('products/show', $data);
        $this->view('layouts/footer');
    }

    public function addToCart()
    {
        if (!$this->isPost()) {
            $this->json(['success' => false, 'message' => 'Invalid request method']);
            return;
        }

        if (!$this->isLoggedIn()) {
            $this->json(['success' => false, 'message' => 'Please login first']);
            return;
        }

        $productId = $this->input('product_id');
        $quantity = (int)$this->input('quantity', 1);
        $userId = $this->session('user_id');

        if (!$productId || $quantity <= 0) {
            $this->json(['success' => false, 'message' => 'Invalid product or quantity']);
            return;
        }

        // Check if product exists and has stock
        $productModel = $this->model('Product');
        $product = $productModel->find($productId);

        if (!$product || $product['stock'] < $quantity) {
            $this->json(['success' => false, 'message' => 'Product not available or insufficient stock']);
            return;
        }

        $cartModel = $this->model('Cart');
        $result = $cartModel->addToCart($userId, $productId, $quantity);

        if ($result) {
            $cartCount = $cartModel->getCartCount($userId);
            $this->json(['success' => true, 'message' => 'Product added to cart', 'cart_count' => $cartCount]);
        } else {
            $this->json(['success' => false, 'message' => 'Failed to add product to cart']);
        }
    }
}
