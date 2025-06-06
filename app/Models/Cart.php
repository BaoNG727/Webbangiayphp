<?php

require_once __DIR__ . '/../Core/Model.php';

class Cart extends Model
{
    protected $table = 'cart';

    public function getUserCart($userId)
    {
        $sql = "SELECT c.*, p.name, p.price, p.sale_price, p.image, p.stock 
                FROM {$this->table} c 
                JOIN products p ON c.product_id = p.id 
                WHERE c.user_id = ?";
        return $this->db->fetchAll($sql, [$userId]);
    }

    public function addToCart($userId, $productId, $quantity = 1)
    {
        // Check if item already exists in cart
        $existing = $this->db->fetch(
            "SELECT * FROM {$this->table} WHERE user_id = ? AND product_id = ?",
            [$userId, $productId]
        );

        if ($existing) {
            // Update quantity
            $newQuantity = $existing['quantity'] + $quantity;
            return $this->db->query(
                "UPDATE {$this->table} SET quantity = ? WHERE user_id = ? AND product_id = ?",
                [$newQuantity, $userId, $productId]
            );
        } else {
            // Add new item
            return $this->create([
                'user_id' => $userId,
                'product_id' => $productId,
                'quantity' => $quantity
            ]);
        }
    }

    public function updateQuantity($userId, $productId, $quantity)
    {
        if ($quantity <= 0) {
            return $this->removeFromCart($userId, $productId);
        }

        return $this->db->query(
            "UPDATE {$this->table} SET quantity = ? WHERE user_id = ? AND product_id = ?",
            [$quantity, $userId, $productId]
        );
    }

    public function removeFromCart($userId, $productId)
    {
        return $this->db->query(
            "DELETE FROM {$this->table} WHERE user_id = ? AND product_id = ?",
            [$userId, $productId]
        );
    }

    public function clearCart($userId)
    {
        return $this->db->query(
            "DELETE FROM {$this->table} WHERE user_id = ?",
            [$userId]
        );
    }

    public function getCartCount($userId)
    {
        $result = $this->db->fetch(
            "SELECT SUM(quantity) as total FROM {$this->table} WHERE user_id = ?",
            [$userId]
        );
        return $result['total'] ?? 0;
    }

    public function getCartTotal($userId)
    {
        $sql = "SELECT SUM(
                    c.quantity * COALESCE(p.sale_price, p.price)
                ) as total 
                FROM {$this->table} c 
                JOIN products p ON c.product_id = p.id 
                WHERE c.user_id = ?";
        
        $result = $this->db->fetch($sql, [$userId]);
        return $result['total'] ?? 0;
    }
}
