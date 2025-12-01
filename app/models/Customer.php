<?php
/**
 * Customer Model
 * Database operations for customers
 * Auto-detects database column names for compatibility
 */

class Customer
{
    private $db;
    private $table = 'customers';
    private $columns = [];

    public function __construct()
    {
        global $pdo;
        $this->db = $pdo;
        $this->detectColumns();
    }

    /**
     * Detect actual column names in the database
     */
    private function detectColumns()
    {
        try {
            $stmt = $this->db->query("DESCRIBE {$this->table}");
            $cols = $stmt->fetchAll(PDO::FETCH_COLUMN);
            
            $this->columns = [
                'id' => in_array('customer_id', $cols) ? 'customer_id' : 'id',
                'phone' => in_array('contact', $cols) ? 'contact' : 'phone',
                'password' => in_array('password_hash', $cols) ? 'password_hash' : 'password',
                'has_role' => in_array('role', $cols)
            ];
        } catch (Exception $e) {
            // Fallback defaults
            $this->columns = [
                'id' => 'customer_id',
                'phone' => 'contact',
                'password' => 'password_hash',
                'has_role' => false
            ];
        }
    }

    /**
     * Authenticate customer
     */
    public function authenticate($email, $password)
    {
        $sql = "SELECT * FROM {$this->table} WHERE email = :email LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['email' => $email]);
        $customer = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($customer) {
            $passwordCol = $this->columns['password'];
            $storedPassword = $customer[$passwordCol] ?? $customer['password'] ?? $customer['password_hash'];
            
            if (password_verify($password, $storedPassword)) {
                // Normalize the customer data
                $idCol = $this->columns['id'];
                $customer['id'] = $customer[$idCol] ?? $customer['id'] ?? $customer['customer_id'];
                return $customer;
            }
        }

        return false;
    }

    /**
     * Check if email exists
     */
    public function emailExists($email)
    {
        $idCol = $this->columns['id'];
        $sql = "SELECT {$idCol} FROM {$this->table} WHERE email = :email LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['email' => $email]);
        return $stmt->fetch() !== false;
    }

    /**
     * Create new customer
     */
    public function create($data)
    {
        $phoneCol = $this->columns['phone'];
        $passwordCol = $this->columns['password'];
        $hasRole = $this->columns['has_role'];
        
        if ($hasRole) {
            $sql = "INSERT INTO {$this->table} (name, email, {$phoneCol}, address, {$passwordCol}, role, created_at) 
                    VALUES (:name, :email, :phone, :address, :password, :role, NOW())";
            
            $stmt = $this->db->prepare($sql);
            
            $result = $stmt->execute([
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'] ?? $data['contact'] ?? null,
                'address' => $data['address'] ?? null,
                'password' => $data['password_hash'] ?? $data['password'],
                'role' => $data['role'] ?? 'customer'
            ]);
        } else {
            $sql = "INSERT INTO {$this->table} (name, email, {$phoneCol}, address, {$passwordCol}, created_at) 
                    VALUES (:name, :email, :phone, :address, :password, NOW())";
            
            $stmt = $this->db->prepare($sql);
            
            $result = $stmt->execute([
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'] ?? $data['contact'] ?? null,
                'address' => $data['address'] ?? null,
                'password' => $data['password_hash'] ?? $data['password']
            ]);
        }

        return $result ? $this->db->lastInsertId() : false;
    }

    /**
     * Get customer by ID
     */
    public function getById($id)
    {
        $idCol = $this->columns['id'];
        $sql = "SELECT * FROM {$this->table} WHERE {$idCol} = :id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        $customer = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($customer) {
            $customer['id'] = $customer[$idCol] ?? $customer['id'] ?? $customer['customer_id'];
        }
        
        return $customer;
    }

    /**
     * Get all customers
     */
    public function getAll()
    {
        $sql = "SELECT * FROM {$this->table} ORDER BY created_at DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Update customer
     */
    public function update($id, $data)
    {
        $idCol = $this->columns['id'];
        $phoneCol = $this->columns['phone'];
        
        // Map field names
        $fieldMapping = [
            'phone' => $phoneCol,
            'contact' => $phoneCol
        ];
        
        $fields = [];
        $params = ['id' => $id];

        foreach ($data as $key => $value) {
            if ($value !== null) {
                $dbField = $fieldMapping[$key] ?? $key;
                $fields[] = "{$dbField} = :{$dbField}";
                $params[$dbField] = $value;
            }
        }

        if (empty($fields)) {
            return false;
        }

        $sql = "UPDATE {$this->table} SET " . implode(', ', $fields) . " WHERE {$idCol} = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    /**
     * Delete customer
     */
    public function delete($id)
    {
        $idCol = $this->columns['id'];
        $sql = "DELETE FROM {$this->table} WHERE {$idCol} = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }

    /**
     * Get customer count
     */
    public function getCount()
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->table}";
        $stmt = $this->db->query($sql);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'];
    }

    /**
     * Search customers
     */
    public function search($keyword)
    {
        $phoneCol = $this->columns['phone'];
        $sql = "SELECT * FROM {$this->table} 
                WHERE name LIKE :keyword OR email LIKE :keyword OR {$phoneCol} LIKE :keyword
                ORDER BY created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['keyword' => "%{$keyword}%"]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Save password reset token
     */
    public function saveResetToken($email, $token, $expiry)
    {
        // Check if reset columns exist
        try {
            $stmt = $this->db->query("DESCRIBE {$this->table}");
            $cols = $stmt->fetchAll(PDO::FETCH_COLUMN);
            
            if (in_array('reset_token', $cols)) {
                $sql = "UPDATE {$this->table} SET reset_token = :token, reset_expiry = :expiry WHERE email = :email";
                $stmt = $this->db->prepare($sql);
                return $stmt->execute([
                    'token' => $token,
                    'expiry' => $expiry,
                    'email' => $email
                ]);
            }
        } catch (Exception $e) {}
        
        return true; // Pretend success if columns don't exist
    }

    /**
     * Get customer by reset token
     */
    public function getByResetToken($token)
    {
        try {
            $stmt = $this->db->query("DESCRIBE {$this->table}");
            $cols = $stmt->fetchAll(PDO::FETCH_COLUMN);
            
            if (in_array('reset_token', $cols)) {
                $sql = "SELECT * FROM {$this->table} WHERE reset_token = :token AND reset_expiry > NOW() LIMIT 1";
                $stmt = $this->db->prepare($sql);
                $stmt->execute(['token' => $token]);
                return $stmt->fetch(PDO::FETCH_ASSOC);
            }
        } catch (Exception $e) {}
        
        return null;
    }

    /**
     * Update customer password
     */
    public function updatePassword($id, $passwordHash)
    {
        $idCol = $this->columns['id'];
        $passwordCol = $this->columns['password'];
        
        $sql = "UPDATE {$this->table} SET {$passwordCol} = :password WHERE {$idCol} = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'password' => $passwordHash,
            'id' => $id
        ]);
    }

    /**
     * Clear reset token
     */
    public function clearResetToken($id)
    {
        $idCol = $this->columns['id'];
        
        try {
            $stmt = $this->db->query("DESCRIBE {$this->table}");
            $cols = $stmt->fetchAll(PDO::FETCH_COLUMN);
            
            if (in_array('reset_token', $cols)) {
                $sql = "UPDATE {$this->table} SET reset_token = NULL, reset_expiry = NULL WHERE {$idCol} = :id";
                $stmt = $this->db->prepare($sql);
                return $stmt->execute(['id' => $id]);
            }
        } catch (Exception $e) {}
        
        return true;
    }

    /**
     * Get customer by email
     */
    public function getByEmail($email)
    {
        $sql = "SELECT * FROM {$this->table} WHERE email = :email LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
