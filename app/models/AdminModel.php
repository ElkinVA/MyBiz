<?php

class AdminModel extends BaseModel {
    protected $table = 'admins';

    public function __construct() {
        parent::__construct();
    }

    public function findByUsername($username) {
        return $this->findOneBy(['username' => $username]);
    }

    public function getByUsername($username) {
        $sql = "SELECT * FROM {$this->table} WHERE username = :username";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['username' => $username]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function verifyPassword($adminId, $password) {
        $admin = $this->find($adminId);
        return $admin && password_verify($password, $admin['password_hash']);
    }

    public function updatePassword($adminId, $newPassword) {
        $passwordHash = password_hash($newPassword, PASSWORD_DEFAULT);
        return $this->update($adminId, ['password_hash' => $passwordHash]);
    }
    
    public function createAdmin($username, $password, $email = null) {
        return $this->create([
            'username' => $username,
            'password_hash' => password_hash($password, PASSWORD_DEFAULT),
            'email' => $email
        ]);
    }
}
