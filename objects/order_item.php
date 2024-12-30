<?php
class OrderItem {
    private $conn;
    private $table_name = "OrderItems";

    public $id;
    public $order_id;
    public $product_id;
    public $quantity;
    public $unit_price;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Fetch all order items for a specific order
    public function getAllByOrderId() {
        $query = "SELECT * FROM {$this->table_name} WHERE order_id = :order_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':order_id', $this->order_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt;
    }

    // Create a new order item
    public function createItem() {
        $query = "INSERT INTO {$this->table_name} (order_id, product_id, quantity, unit_price) 
                  VALUES (:order_id, :product_id, :quantity, :unit_price)";
        $stmt = $this->conn->prepare($query);

        // Bind values
        $stmt->bindParam(':order_id', $this->order_id, PDO::PARAM_INT);
        $stmt->bindParam(':product_id', $this->product_id, PDO::PARAM_INT);
        $stmt->bindParam(':quantity', $this->quantity, PDO::PARAM_INT);
        $stmt->bindParam(':unit_price', $this->unit_price, PDO::PARAM_STR);

        return $stmt->execute();
    }
}
?>
