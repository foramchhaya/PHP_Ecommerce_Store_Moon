<?php
class Order {
    private $conn;
    private $table = "Orders";

    public $id;
    public $order_code;
    public $user_id;
    public $total_amount;
    public $address_first_name;
    public $address_last_name;
    public $address_street;
    public $address_city;
    public $address_state;
    public $address_postal_code;
    public $address_country;
    public $address_mobile;
    public $address_email;
    public $created_on;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Fetch all orders, ordered by creation date
    public function getAllOrders() {
        $query = "SELECT * FROM {$this->table} ORDER BY created_on DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Fetch a single order by its ID
    public function getOrderById() {
        $query = "SELECT * FROM {$this->table} WHERE id = :orderId LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':orderId', $this->id, PDO::PARAM_INT);
        $stmt->execute();
        $order = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($order) {
            $this->user_id = $order['user_id'];
            $this->total_amount = $order['total_amount'];
            $this->created_on = $order['created_on'];
            $this->order_code = $order['order_code'];
            $this->address_first_name = $order['address_first_name'];
            $this->address_last_name = $order['address_last_name'];
            $this->address_street = $order['address_street'];
            $this->address_city = $order['address_city'];
            $this->address_state = $order['address_state'];
            $this->address_postal_code = $order['address_postal_code'];
            $this->address_country = $order['address_country'];
            $this->address_mobile = $order['address_mobile'];
            $this->address_email = $order['address_email'];
        }
    }

    // Create a new order
    public function createOrder() {
        $query = "INSERT INTO {$this->table} (user_id, order_code, total_amount, address_first_name, address_last_name, address_street, address_city, address_state, address_postal_code, address_country, address_mobile, address_email) 
                  VALUES (:user_id, :order_code, :total_amount, :address_first_name, :address_last_name, :address_street, :address_city, :address_state, :address_postal_code, :address_country, :address_mobile, :address_email)";
        $stmt = $this->conn->prepare($query);

        // Generate order code
        $this->order_code = $this->generateOrderCode();

        // Bind values
        $stmt->bindParam(':user_id', $this->user_id, PDO::PARAM_INT);
        $stmt->bindParam(':order_code', $this->order_code, PDO::PARAM_STR);
        $stmt->bindParam(':total_amount', $this->total_amount, PDO::PARAM_STR);
        $stmt->bindParam(':address_first_name', $this->address_first_name, PDO::PARAM_STR);
        $stmt->bindParam(':address_last_name', $this->address_last_name, PDO::PARAM_STR);
        $stmt->bindParam(':address_street', $this->address_street, PDO::PARAM_STR);
        $stmt->bindParam(':address_city', $this->address_city, PDO::PARAM_STR);
        $stmt->bindParam(':address_state', $this->address_state, PDO::PARAM_STR);
        $stmt->bindParam(':address_postal_code', $this->address_postal_code, PDO::PARAM_STR);
        $stmt->bindParam(':address_country', $this->address_country, PDO::PARAM_STR);
        $stmt->bindParam(':address_mobile', $this->address_mobile, PDO::PARAM_STR);
        $stmt->bindParam(':address_email', $this->address_email, PDO::PARAM_STR);

        if ($stmt->execute()) {
            echo "Order created successfully";
            return true;
        }
        return false;
    }

    // Generate a unique order code
    private function generateOrderCode() {
        $timestamp = microtime(true);
        $randomNumber = mt_rand(1000, 9999);
        return strtoupper(substr(md5($timestamp . $randomNumber), 0, 8));
    }
}
?>
