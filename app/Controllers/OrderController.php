<?php

require_once __DIR__ . '/../Core/Controller.php';
require_once __DIR__ . '/../Core/Database.php';
require_once __DIR__ . '/../Core/Email.php';

class OrderController extends Controller
{
    public function index()
    {
        $this->requireLogin();

        $orderModel = $this->model('Order');
        $userId = $this->session('user_id');
        
        $orders = $orderModel->getUserOrders($userId);

        $data = [
            'title' => 'My Orders - Nike Shoe Store',
            'orders' => $orders
        ];

        $this->view('layouts/header', $data);
        $this->view('orders/index', $data);
        $this->view('layouts/footer');
    }

    public function show($orderId)
    {
        $this->requireLogin();

        $orderModel = $this->model('Order');
        $userId = $this->session('user_id');
        
        $order = $orderModel->getOrderWithItems($orderId);

        // Check if order belongs to current user
        if (!$order || $order['user_id'] != $userId) {
            http_response_code(404);
            $this->view('layouts/header', ['title' => 'Order Not Found']);
            $this->view('errors/404');
            $this->view('layouts/footer');
            return;
        }

        $data = [
            'title' => 'Order #' . str_pad($orderId, 6, '0', STR_PAD_LEFT) . ' - Nike Shoe Store',
            'order' => $order
        ];

        $this->view('layouts/header', $data);
        $this->view('orders/show', $data);
        $this->view('layouts/footer');
    }

    /**
     * Update order status (typically called from admin panel)
     * This method sends email notifications when order status changes
     */
    public function updateStatus($orderId)
    {
        if (!$this->isPost()) {
            $this->redirect('/');
            return;
        }

        $orderModel = $this->model('Order');
        $order = $orderModel->getOrderWithItems($orderId);

        if (!$order) {
            http_response_code(404);
            return;
        }

        $newStatus = $this->input('status');
        $validStatuses = ['pending', 'processing', 'shipped', 'delivered', 'cancelled'];

        if (!in_array($newStatus, $validStatuses)) {
            $this->session('error', 'Invalid status');
            $this->redirect('/admin/orders/' . $orderId);
            return;
        }

        $oldStatus = $order['status'];

        if ($orderModel->updateStatus($orderId, $newStatus)) {
            // Send email notification if status changed
            if ($oldStatus !== $newStatus) {
                try {                    // Get user email from order or user table
                    $userModel = $this->model('User');
                    $user = $userModel->find($order['user_id']);
                    
                    if ($user && $user['email']) {
                        $emailService = new Email();
                        $emailService->sendOrderStatusUpdate($order, $user['email'], $oldStatus, $newStatus);
                    }
                } catch (Exception $emailError) {
                    error_log("Failed to send order status update email: " . $emailError->getMessage());
                }
            }

            $this->session('success', 'Order status updated successfully');
        } else {
            $this->session('error', 'Failed to update order status');
        }

        $this->redirect('/admin/orders/' . $orderId);
    }
}
