<?php
namespace App\Core;

use App\Models\AdminModel;

class Auth {
    private $session;
    private $adminModel;

    public function __construct() {
        $this->session = new Session();
        $this->adminModel = new AdminModel();
    }

    public function login($username, $password) {
        $admin = $this->adminModel->getByUsername($username);
        
        if ($admin && password_verify($password, $admin['password_hash'])) {
            $this->session->set('admin_id', $admin['id']);
            $this->session->set('admin_username', $admin['username']);
            $this->session->set('admin_logged_in', true);
            return true;
        }
        
        return false;
    }

    public function logout() {
        $this->session->destroy();
    }

    public function isLoggedIn() {
        return $this->session->get('admin_logged_in') === true;
    }

    public function getAdminId() {
        return $this->session->get('admin_id');
    }

    public function getAdminUsername() {
        return $this->session->get('admin_username');
    }
}
?>