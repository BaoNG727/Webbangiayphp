<?php

require_once __DIR__ . '/../Core/Controller.php';
require_once __DIR__ . '/../Core/Database.php';

class CartController extends Controller
{
    public function index()
    {
        $this->requireLogin();

        $cartModel = $this->model('Cart');
        $userId = $this->session('user_id');
        
        $cartItems = $cartModel->getUserCart($userId);
        $cartTotal = $cartModel->getCartTotal($userId);

        $data = [
            'title' => 'Shopping Cart - Nike Shoe Store',
            'cart_items' => $cartItems,
            'cart_total' => $cartTotal
        ];

        $this->view('layouts/header', $data);
        $this->view('cart/index', $data);
        $this->view('layouts/footer');
    }

    public function update()
    {
        if (!$this->isPost()) {
            $this->json(['success' => false, 'message' => 'Invalid request method']);
            return;
        }

        $this->requireLogin();

        $productId = $this->input('product_id');
        $quantity = (int)$this->input('quantity');
        $userId = $this->session('user_id');

        if (!$productId) {
            $this->json(['success' => false, 'message' => 'Product ID is required']);
            return;
        }

        $cartModel = $this->model('Cart');
        
        if ($quantity <= 0) {
            $result = $cartModel->removeFromCart($userId, $productId);
            $message = 'Item removed from cart';
        } else {
            $result = $cartModel->updateQuantity($userId, $productId, $quantity);
            $message = 'Cart updated';
        }

        if ($result) {
            $cartCount = $cartModel->getCartCount($userId);
            $cartTotal = $cartModel->getCartTotal($userId);
            
            $this->json([
                'success' => true, 
                'message' => $message,
                'cart_count' => $cartCount,
                'cart_total' => $cartTotal
            ]);
        } else {
            $this->json(['success' => false, 'message' => 'Failed to update cart']);
        }
    }

    public function remove()
    {
        if (!$this->isPost()) {
            $this->json(['success' => false, 'message' => 'Invalid request method']);
            return;
        }

        $this->requireLogin();

        $productId = $this->input('product_id');
        $userId = $this->session('user_id');

        if (!$productId) {
            $this->json(['success' => false, 'message' => 'Product ID is required']);
            return;
        }

        $cartModel = $this->model('Cart');
        $result = $cartModel->removeFromCart($userId, $productId);

        if ($result) {
            $cartCount = $cartModel->getCartCount($userId);
            $cartTotal = $cartModel->getCartTotal($userId);
            
            $this->json([
                'success' => true, 
                'message' => 'Item removed from cart',
                'cart_count' => $cartCount,
                'cart_total' => $cartTotal
            ]);
        } else {
            $this->json(['success' => false, 'message' => 'Failed to remove item']);
        }
    }

    public function clear()
    {
        if (!$this->isPost()) {
            $this->json(['success' => false, 'message' => 'Invalid request method']);
            return;
        }

        $this->requireLogin();

        $cartModel = $this->model('Cart');
        $userId = $this->session('user_id');
        
        $result = $cartModel->clearCart($userId);

        if ($result) {
            $this->json(['success' => true, 'message' => 'Cart cleared']);
        } else {
            $this->json(['success' => false, 'message' => 'Failed to clear cart']);
        }
    }
}
