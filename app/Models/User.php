<?php

require_once __DIR__ . '/../Core/Model.php';

class User extends Model
{
    protected $table = 'users';

    public function findByUsername($username)
    {
        $sql = "SELECT * FROM {$this->table} WHERE username = ? OR email = ?";
        return $this->db->fetch($sql, [$username, $username]);
    }

    public function findByEmail($email)
    {
        $sql = "SELECT * FROM {$this->table} WHERE email = ?";
        return $this->db->fetch($sql, [$email]);
    }

    public function createUser($data)
    {
        // Hash password
        if (isset($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }

        // Set default role if not provided
        if (!isset($data['role'])) {
            $data['role'] = 'customer';
        }

        return $this->create($data);
    }

    public function verifyPassword($password, $hashedPassword)
    {
        return password_verify($password, $hashedPassword);
    }

    public function updatePassword($id, $newPassword)
    {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        return $this->update($id, ['password' => $hashedPassword]);
    }

    public function getCustomers()
    {
        return $this->where(['role' => 'customer']);
    }

    public function isEmailTaken($email, $excludeId = null)
    {
        $sql = "SELECT id FROM {$this->table} WHERE email = ?";
        $params = [$email];

        if ($excludeId) {
            $sql .= " AND id != ?";
            $params[] = $excludeId;
        }

        $result = $this->db->fetch($sql, $params);
        return $result !== false;
    }

    public function isUsernameTaken($username, $excludeId = null)
    {
        $sql = "SELECT id FROM {$this->table} WHERE username = ?";
        $params = [$username];

        if ($excludeId) {
            $sql .= " AND id != ?";
            $params[] = $excludeId;
        }

        $result = $this->db->fetch($sql, $params);
        return $result !== false;
    }
}
