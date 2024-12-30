<?php
class User {
    private $conn;
    private $table_name = "Users";

    public $id;
    public $role;
    public $email_address;
    public $password_hash;
    public $active;

    private static $instance = null;

    private function __construct($db) {
        $this->conn = $db;
    }

    public static function getInstance($db) {
        if (self::$instance == null) {
            self::$instance = new User($db);
        }
        return self::$instance;
    }


    public function fetchUser() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = ? LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $this->id = $row['id'];
            $this->role = $row['role'];
            $this->email_address = $row['email_address'];
            $this->password_hash = $row['password_hash'];
            $this->active = $row['active'];
        }
    }

    public function register() {
        $query = "INSERT INTO " . $this->table_name . " (email_address, password_hash, role) VALUES (:email_address, :password_hash, :role)";
        $stmt = $this->conn->prepare($query);

        // Sanitize
        $this->email_address = htmlspecialchars(strip_tags($this->email_address));
        $this->password_hash = htmlspecialchars(strip_tags($this->password_hash));
        $this->role = htmlspecialchars(strip_tags($this->role));

        // Bind values
        $stmt->bindParam(":email_address", $this->email_address);
        $stmt->bindParam(":password_hash", password_hash($this->password_hash, PASSWORD_BCRYPT));
        $stmt->bindParam(":role", $this->role);

        return $stmt->execute();
    }

    public function authenticate() {
        $query = "SELECT id, password_hash FROM " . $this->table_name . " WHERE email_address = :email_address LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email_address', $this->email_address);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);


        if ($row && password_verify($this->password_hash, $row['password_hash'])) {
            $this->id = $row['id'];
            $this->fetchUser();

            if ($this->active == 0) {
                return false;
            }
            return true;
        }
        return false;
    }

    public function changePassword($oldPassword, $newPassword) {
        if (empty($oldPassword) || empty($newPassword)) {
            return false; // or throw an exception if you prefer
        }

        // Verify the old password
        $query = "SELECT password_hash FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $this->id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row || !password_verify($oldPassword, $row['password_hash'])) {
            return false;
        }

        // Update to the new password
        $query = "UPDATE " . $this->table_name . " SET password_hash = :password_hash WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        // Sanitize
        $newPassword = htmlspecialchars(strip_tags($newPassword));
        $this->id = htmlspecialchars(strip_tags($this->id));

        // Bind values
        $stmt->bindParam(":password_hash", password_hash($newPassword, PASSWORD_BCRYPT));
        $stmt->bindParam(":id", $this->id);

        return $stmt->execute();
    }

    public function deactivate() {
        $query = "UPDATE " . $this->table_name . " SET active = 0 WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);
        return $stmt->execute();
    }

    // Method to check if the user is authenticated
    public function isAuthenticated() {
        if (isset($_SESSION['user_id']) && isset($_SESSION['role'])) {
            $this->id = $_SESSION['user_id'];
            $this->role = $_SESSION['role'];
            $this->fetchUser();
            return true;
        }
        return false;
    }

    public function verifyUser($allowedRoles) {

      
        // Check if user is logged in
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
            $error_message = urlencode("You need to be logged in to access this page");
            header("Location: signup.php?message=$error_message");
            exit();
        }
    
        // Get user's role from session
        $user_role = $_SESSION['role'];
    
        // Check if user's role is in the allowed roles array
        if (!in_array($user_role, $allowedRoles)) {
            $error_message = urlencode("You are not authorized to access this page");
            header("Location: 404_page.php?message=$error_message");
            exit();
        }
    }

    public function getAllUsers() {
        $query = "SELECT id, email_address, role, active FROM Users where role = 'user'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function updateUserStatus($id, $status) {
        $query = "UPDATE " . $this->table_name . " SET active = :active WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":active", $status, PDO::PARAM_INT);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);

        return $stmt->execute();
    }

}
?>
