<?php

require_once __DIR__ . '/../Core/Controller.php';
require_once __DIR__ . '/../Core/Database.php';
require_once __DIR__ . '/../Core/Email.php';

class CheckoutController extends Controller
{
    public function index()
    {
        $this->requireLogin();

        $cartModel = $this->model('Cart');
        $userId = $this->session('user_id');
        
        $cartItems = $cartModel->getUserCart($userId);
        $cartTotal = $cartModel->getCartTotal($userId);        // Redirect if cart is empty
        if (empty($cartItems)) {
            $this->redirect('/Webgiay/cart');
            return;
        }

        $data = [
            'title' => 'Checkout - Nike Shoe Store',
            'cart_items' => $cartItems,
            'cart_total' => $cartTotal,
            'shipping_cost' => $cartTotal >= 50 ? 0 : 5.99,
            'final_total' => $cartTotal + ($cartTotal >= 50 ? 0 : 5.99)
        ];

        $this->view('layouts/header', $data);
        $this->view('checkout/index', $data);
        $this->view('layouts/footer');
    }    public function process()
    {
        if (!$this->isPost()) {
            $this->redirect('/Webgiay/checkout');
            return;
        }

        $this->requireLogin();

        $cartModel = $this->model('Cart');
        $orderModel = $this->model('Order');
        $userId = $this->session('user_id');

        $cartItems = $cartModel->getUserCart($userId);
        $cartTotal = $cartModel->getCartTotal($userId);        if (empty($cartItems)) {
            $this->session('error', 'Your cart is empty');
            $this->redirect('/Webgiay/cart');
            return;
        }

        // Get form data
        $shipping = [
            'first_name' => trim($this->input('first_name')),
            'last_name' => trim($this->input('last_name')),
            'email' => trim($this->input('email')),
            'phone' => trim($this->input('phone')),
            'address' => trim($this->input('address')),
            'city' => trim($this->input('city')),
            'state' => trim($this->input('state')),
            'zip' => trim($this->input('zip')),
            'country' => trim($this->input('country'))
        ];

        $paymentMethod = $this->input('payment_method');

        // Validation
        $errors = [];
        
        foreach ($shipping as $key => $value) {
            if (empty($value)) {
                $errors[] = ucfirst(str_replace('_', ' ', $key)) . ' is required';
            }
        }

        if (empty($paymentMethod)) {
            $errors[] = 'Payment method is required';
        }

        if (!filter_var($shipping['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Valid email is required';
        }        if (!empty($errors)) {
            $this->session('checkout_errors', $errors);
            $this->session('checkout_data', array_merge($shipping, ['payment_method' => $paymentMethod]));
            $this->redirect('/Webgiay/checkout');
            return;
        }

        try {
            // Calculate totals
            $subtotal = $cartTotal;
            $shippingCost = $cartTotal >= 50 ? 0 : 5.99;
            $finalTotal = $subtotal + $shippingCost;

            // Create shipping address string
            $shippingAddress = implode(', ', [
                $shipping['first_name'] . ' ' . $shipping['last_name'],
                $shipping['address'],
                $shipping['city'] . ', ' . $shipping['state'] . ' ' . $shipping['zip'],
                $shipping['country']
            ]);

            // Create order
            $orderData = [
                'user_id' => $userId,
                'total_amount' => $finalTotal,
                'status' => 'pending',
                'shipping_address' => $shippingAddress,
                'payment_method' => $paymentMethod
            ];            $orderId = $orderModel->createOrder($orderData);

            if ($orderId) {
                // Add order items
                foreach ($cartItems as $item) {
                    $price = ($item['sale_price'] > 0) ? $item['sale_price'] : $item['price'];
                    $orderItemData = [
                        'order_id' => $orderId,
                        'product_id' => $item['product_id'],
                        'quantity' => $item['quantity'],
                        'price' => $price
                    ];
                    $orderModel->addOrderItem($orderItemData);
                }

                // Clear cart
                $cartModel->clearCart($userId);                // Send order confirmation email
                try {
                    $emailService = new Email();
                    $order = $orderModel->getOrderWithItems($orderId);
                    $emailService->sendOrderConfirmation($order, $shipping['email']);
                } catch (Exception $emailError) {
                    // Log email error but don't fail the order
                    error_log("Failed to send order confirmation email: " . $emailError->getMessage());
                }                // Set success message and redirect
                $this->session('order_success', $orderId);
                $this->redirect('/Webgiay/checkout/success');
            } else {
                throw new Exception('Failed to create order');
            }        } catch (Exception $e) {
            $this->session('error', 'Order processing failed. Please try again.');
            $this->redirect('/Webgiay/checkout');
        }
    }

    public function success()
    {
        $this->requireLogin();        $orderId = $this->session('order_success');
        if (!$orderId) {
            $this->redirect('/Webgiay/');
            return;
        }

        // Clear the success session
        unset($_SESSION['order_success']);

        $orderModel = $this->model('Order');
        $order = $orderModel->getOrderWithItems($orderId);

        $data = [
            'title' => 'Order Confirmation - Nike Shoe Store',
            'order' => $order
        ];

        $this->view('layouts/header', $data);
        $this->view('checkout/success', $data);
        $this->view('layouts/footer');
    }
}
