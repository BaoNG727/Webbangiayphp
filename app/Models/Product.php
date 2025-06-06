<?php

require_once __DIR__ . '/../Core/Model.php';

class Product extends Model
{
    protected $table = 'products';

    public function getFeatured($limit = 4)
    {
        $sql = "SELECT * FROM {$this->table} WHERE stock > 0 ORDER BY id DESC LIMIT ?";
        return $this->db->fetchAll($sql, [$limit]);
    }

    public function getSaleProducts($limit = 4)
    {
        $sql = "SELECT * FROM {$this->table} 
                WHERE sale_price IS NOT NULL AND sale_price > 0 AND stock > 0 
                ORDER BY (price - sale_price) DESC LIMIT ?";
        return $this->db->fetchAll($sql, [$limit]);
    }

    public function getByCategory($category)
    {
        $sql = "SELECT * FROM {$this->table} WHERE category = ? AND stock > 0";
        return $this->db->fetchAll($sql, [$category]);
    }

    public function search($filters = [])
    {
        $sql = "SELECT * FROM {$this->table} WHERE stock > 0";
        $params = [];

        if (!empty($filters['category'])) {
            $sql .= " AND category = ?";
            $params[] = $filters['category'];
        }

        if (!empty($filters['sale'])) {
            $sql .= " AND sale_price IS NOT NULL AND sale_price > 0";
        }

        if (!empty($filters['search'])) {
            $sql .= " AND (name LIKE ? OR description LIKE ? OR category LIKE ?)";
            $searchTerm = '%' . $filters['search'] . '%';
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }

        // Add sorting
        $sort = $filters['sort'] ?? 'newest';
        switch ($sort) {
            case 'price_low':
                $sql .= " ORDER BY COALESCE(sale_price, price) ASC";
                break;
            case 'price_high':
                $sql .= " ORDER BY COALESCE(sale_price, price) DESC";
                break;
            case 'name_asc':
                $sql .= " ORDER BY name ASC";
                break;
            case 'name_desc':
                $sql .= " ORDER BY name DESC";
                break;
            default: // newest
                $sql .= " ORDER BY id DESC";
        }

        return $this->db->fetchAll($sql, $params);
    }

    public function getCategories()
    {
        $sql = "SELECT DISTINCT category FROM {$this->table} 
                WHERE category IS NOT NULL AND category != '' 
                ORDER BY category";
        return $this->db->fetchAll($sql);
    }

    public function updateStock($id, $quantity)
    {
        $sql = "UPDATE {$this->table} SET stock = stock - ? WHERE id = ?";
        return $this->db->query($sql, [$quantity, $id]);
    }

    public function getLowStock($limit = 5)
    {
        $sql = "SELECT * FROM {$this->table} WHERE stock <= 5 AND stock > 0 ORDER BY stock ASC LIMIT ?";
        return $this->db->fetchAll($sql, [$limit]);
    }
}
