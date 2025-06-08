<?php

require_once __DIR__ . '/../Core/Model.php';

class DiscountCode extends Model
{
    protected $table = 'discount_codes';

    /**
     * Validate and apply discount code
     */
    public function validateCode($code, $userId, $orderAmount)
    {
        $sql = "SELECT dc.*, 
                       COALESCE(SUM(CASE WHEN dcu.user_id = ? THEN 1 ELSE 0 END), 0) as user_usage
                FROM discount_codes dc
                LEFT JOIN discount_code_usage dcu ON dc.id = dcu.discount_code_id
                WHERE dc.code = ? 
                AND dc.is_active = 1
                AND dc.valid_from <= NOW()
                AND dc.valid_until >= NOW()
                GROUP BY dc.id";
        
        $discountCode = $this->db->fetch($sql, [$userId, $code]);
        
        if (!$discountCode) {
            return ['valid' => false, 'message' => 'Invalid or expired discount code'];
        }

        // Check minimum order amount
        if ($orderAmount < $discountCode['minimum_order_amount']) {
            return [
                'valid' => false, 
                'message' => 'Minimum order amount of ' . number_format($discountCode['minimum_order_amount']) . ' VND required'
            ];
        }

        // Check usage limit
        if ($discountCode['usage_limit'] && $discountCode['usage_count'] >= $discountCode['usage_limit']) {
            return ['valid' => false, 'message' => 'Discount code usage limit exceeded'];
        }

        // Check user usage limit
        if ($discountCode['user_usage_limit'] && $discountCode['user_usage'] >= $discountCode['user_usage_limit']) {
            return ['valid' => false, 'message' => 'You have already used this discount code the maximum number of times'];
        }

        // Calculate discount amount
        $discountAmount = $this->calculateDiscountAmount($discountCode, $orderAmount);

        return [
            'valid' => true,
            'discount_code' => $discountCode,
            'discount_amount' => $discountAmount,
            'message' => 'Discount code applied successfully'
        ];
    }

    /**
     * Calculate discount amount based on type
     */
    private function calculateDiscountAmount($discountCode, $orderAmount)
    {
        if ($discountCode['type'] === 'percentage') {
            $discountAmount = ($orderAmount * $discountCode['value']) / 100;
            
            // Apply maximum discount limit if set
            if ($discountCode['maximum_discount_amount'] && $discountAmount > $discountCode['maximum_discount_amount']) {
                $discountAmount = $discountCode['maximum_discount_amount'];
            }
        } else {
            // Fixed amount discount
            $discountAmount = $discountCode['value'];
        }

        // Ensure discount doesn't exceed order amount
        return min($discountAmount, $orderAmount);
    }

    /**
     * Record discount code usage
     */
    public function recordUsage($discountCodeId, $userId, $orderId, $discountAmount)
    {
        $this->db->beginTransaction();
        
        try {
            // Insert usage record
            $sql = "INSERT INTO discount_code_usage (discount_code_id, user_id, order_id, discount_amount) 
                    VALUES (?, ?, ?, ?)";
            $this->db->execute($sql, [$discountCodeId, $userId, $orderId, $discountAmount]);

            // Update usage count
            $sql = "UPDATE discount_codes SET usage_count = usage_count + 1 WHERE id = ?";
            $this->db->execute($sql, [$discountCodeId]);

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollback();
            return false;
        }
    }

    /**
     * Get all discount codes with stats
     */
    public function getAllWithStats()
    {
        $sql = "SELECT dc.*, 
                       u.username as created_by_username,
                       CASE 
                           WHEN dc.valid_until < NOW() THEN 'Expired'
                           WHEN dc.valid_from > NOW() THEN 'Scheduled'
                           WHEN dc.is_active = 0 THEN 'Inactive'
                           WHEN dc.usage_limit IS NOT NULL AND dc.usage_count >= dc.usage_limit THEN 'Used Up'
                           ELSE 'Active'
                       END as status
                FROM discount_codes dc
                LEFT JOIN users u ON dc.created_by = u.id
                ORDER BY dc.created_at DESC";
        
        return $this->db->fetchAll($sql);
    }

    /**
     * Get discount code usage history
     */
    public function getUsageHistory($discountCodeId = null, $limit = 50)
    {
        $sql = "SELECT dcu.*, dc.code, u.username, o.id as order_number
                FROM discount_code_usage dcu
                JOIN discount_codes dc ON dcu.discount_code_id = dc.id
                JOIN users u ON dcu.user_id = u.id
                JOIN orders o ON dcu.order_id = o.id";
        
        $params = [];
        if ($discountCodeId) {
            $sql .= " WHERE dcu.discount_code_id = ?";
            $params[] = $discountCodeId;
        }
        
        $sql .= " ORDER BY dcu.used_at DESC LIMIT ?";
        $params[] = $limit;
        
        return $this->db->fetchAll($sql, $params);
    }

    /**
     * Get active codes for public display
     */
    public function getActiveCodes()
    {
        $sql = "SELECT code, description, type, value, minimum_order_amount, maximum_discount_amount, valid_until
                FROM discount_codes 
                WHERE is_active = 1 
                AND valid_from <= NOW() 
                AND valid_until >= NOW()
                AND (usage_limit IS NULL OR usage_count < usage_limit)
                ORDER BY value DESC";
        
        return $this->db->fetchAll($sql);
    }

    /**
     * Create new discount code
     */
    public function createCode($data)
    {
        $data['code'] = strtoupper(trim($data['code']));
        $data['usage_count'] = 0;
        return $this->create($data);
    }

    /**
     * Update discount code
     */
    public function updateCode($id, $data)
    {
        if (isset($data['code'])) {
            $data['code'] = strtoupper(trim($data['code']));
        }
        return $this->update($id, $data);
    }

    /**
     * Check if code exists (excluding specific ID)
     */
    public function codeExists($code, $excludeId = null)
    {
        $sql = "SELECT id FROM discount_codes WHERE code = ?";
        $params = [$code];
        
        if ($excludeId) {
            $sql .= " AND id != ?";
            $params[] = $excludeId;
        }
        
        return $this->db->fetch($sql, $params) !== false;
    }

    /**
     * Get statistics
     */
    public function getStats()
    {
        $stats = [];
        
        // Total codes
        $stats['total_codes'] = $this->count();
        
        // Active codes
        $sql = "SELECT COUNT(*) as count FROM discount_codes 
                WHERE is_active = 1 AND valid_from <= NOW() AND valid_until >= NOW()";
        $result = $this->db->fetch($sql);
        $stats['active_codes'] = $result['count'];
        
        // Expired codes
        $sql = "SELECT COUNT(*) as count FROM discount_codes WHERE valid_until < NOW()";
        $result = $this->db->fetch($sql);
        $stats['expired_codes'] = $result['count'];
        
        // Total usage
        $sql = "SELECT SUM(usage_count) as total_usage FROM discount_codes";
        $result = $this->db->fetch($sql);
        $stats['total_usage'] = $result['total_usage'] ?: 0;
        
        // Total savings
        $sql = "SELECT SUM(discount_amount) as total_savings FROM discount_code_usage";
        $result = $this->db->fetch($sql);
        $stats['total_savings'] = $result['total_savings'] ?: 0;
        
        return $stats;
    }
}
