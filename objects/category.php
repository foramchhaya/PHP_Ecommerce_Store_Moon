<?php
class Category {
    private $conn;
    private $table_name = "Categories";

    public $id;
    public $name;
    public $details;
    public $isActive;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Fetch all categories
    public function getAll() {
        $query = "SELECT * FROM {$this->table_name}";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Fetch a single category by ID
    public function getById() {
        $query = "SELECT * FROM {$this->table_name} WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
        $stmt->execute();
        $category = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($category) {
            $this->name = $category['category_name'];
            $this->details = $category['details'];
            $this->isActive = $category['active'];
        }
    }

    // Soft delete (inactivate) a category by ID
    public function deactivate() {
        $query = "UPDATE {$this->table_name} SET active = 0 WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
?>
