<?php

require_once __DIR__ . '/../Core/Model.php';

class Order extends Model
{
    protected $table = 'orders';

    public function createOrder($data)
    {
        return $this->create($data);
    }

    public function getUserOrders($userId)
    {
        $sql = "SELECT * FROM {$this->table} WHERE user_id = ? ORDER BY created_at DESC";
        return $this->db->fetchAll($sql, [$userId]);
    }

    public function getOrderWithItems($orderId)
    {
        $order = $this->find($orderId);
        if ($order) {
            $order['items'] = $this->getOrderItems($orderId);
        }
        return $order;
    }

    public function getOrderItems($orderId)
    {
        $sql = "SELECT oi.*, p.name, p.image 
                FROM order_items oi 
                JOIN products p ON oi.product_id = p.id 
                WHERE oi.order_id = ?";
        return $this->db->fetchAll($sql, [$orderId]);
    }

    public function addOrderItem($orderItemData)
    {
        $sql = "INSERT INTO order_items (order_id, product_id, quantity, price) 
                VALUES (:order_id, :product_id, :quantity, :price)";
        return $this->db->query($sql, $orderItemData);
    }

    public function getRecentOrders($limit = 5)
    {
        $sql = "SELECT o.*, u.username, u.email 
                FROM {$this->table} o 
                JOIN users u ON o.user_id = u.id 
                ORDER BY o.created_at DESC LIMIT ?";
        return $this->db->fetchAll($sql, [$limit]);
    }

    public function updateStatus($orderId, $status)
    {
        return $this->update($orderId, ['status' => $status]);
    }

    public function getOrdersByStatus($status)
    {
        return $this->where(['status' => $status]);
    }

    public function getTotalSales()
    {
        $sql = "SELECT SUM(total_amount) as total FROM {$this->table} WHERE status != 'cancelled'";
        $result = $this->db->fetch($sql);
        return $result['total'] ?? 0;
    }
}
