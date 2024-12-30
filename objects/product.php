<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

class Product
{
    private $conn;
    private $table = "Products";

    public $id;
    public $product_name;
    public $brief_description;
    public $full_description;
    public $unit_price;
    public $category_id;
    public $category_name; 
    public $image_file;
    public $created_on;
    public $active;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Fetch all active products
    public function getAllActiveProducts()
    {
        $query = "SELECT p.*, c.category_name 
                  FROM {$this->table} p 
                  JOIN Categories c ON p.category_id = c.id 
                  WHERE p.active = 1 
                  ORDER BY p.created_on DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    //get rendom 3 products
    public function getRendomProducts()
    {
        $query = "SELECT p.*, c.category_name 
                  FROM {$this->table} p 
                  JOIN Categories c ON p.category_id = c.id 
                  WHERE p.active = 1 
                  ORDER BY RAND() 
                  LIMIT 3";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Fetch a single product by its ID
    public function getProductById()
    {
        $query = "SELECT p.*, c.category_name 
                  FROM {$this->table} p 
                  JOIN Categories c ON p.category_id = c.id 
                  WHERE p.id = :productId 
                  LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':productId', $this->id, PDO::PARAM_INT);
        $stmt->execute();
        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($product) {
            $this->product_name = $product['product_name'];
            $this->brief_description = $product['brief_description'];
            $this->full_description = $product['full_description'];
            $this->unit_price = $product['unit_price'];
            $this->category_id = $product['category_id'];
            $this->category_name = $product['category_name']; 
            $this->image_file = $product['image_file'];
            $this->created_on = $product['created_on'];
            $this->active = $product['active'];
        }
    }

    // Create a new product
    public function createProduct()
    {
        $query = "INSERT INTO {$this->table} 
                  (product_name, brief_description, full_description, unit_price, category_id, image_file) 
                  VALUES (:product_name, :brief_description, :full_description, :unit_price, :category_id, :image_file)";
        $stmt = $this->conn->prepare($query);

        // Bind values
        $stmt->bindParam(':product_name', $this->product_name);
        $stmt->bindParam(':brief_description', $this->brief_description);
        $stmt->bindParam(':full_description', $this->full_description);
        $stmt->bindParam(':unit_price', $this->unit_price);
        $stmt->bindParam(':category_id', $this->category_id);
        $stmt->bindParam(':image_file', $this->image_file);

        return $stmt->execute();
    }

    // Update product details
    public function updateProduct()
    {
        $query = "UPDATE {$this->table} 
                  SET product_name = :product_name, 
                      brief_description = :brief_description, 
                      full_description = :full_description, 
                      unit_price = :unit_price, 
                      category_id = :category_id, 
                      image_file = :image_file 
                  WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        // Bind parameters
        $stmt->bindParam(':product_name', $this->product_name);
        $stmt->bindParam(':brief_description', $this->brief_description);
        $stmt->bindParam(':full_description', $this->full_description);
        $stmt->bindParam(':unit_price', $this->unit_price);
        $stmt->bindParam(':category_id', $this->category_id);
        $stmt->bindParam(':image_file', $this->image_file);
        $stmt->bindParam(':id', $this->id);

        return $stmt->execute();
    }

    // Activate a product
    public function activateProduct()
    {
        $query = "UPDATE {$this->table} SET active = 1 WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);
        return $stmt->execute();
    }

    // Deactivate a product
    public function deactivateProduct()
    {
        $query = "UPDATE {$this->table} SET active = 0 WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);
        return $stmt->execute();
    }

    // Fetch products by category
    public function getProductsByCategory($categoryId)
    {
        $query = "SELECT p.id, p.product_name, p.brief_description, p.unit_price, p.image_file, c.category_name, p.active
              FROM Products p
              JOIN Categories c ON p.category_id = c.id
              WHERE c.active = 1";

        if (!empty($categoryId)) {
            $query .= " AND p.category_id = :category_id";
        }

        $stmt = $this->conn->prepare($query);

        if (!empty($categoryId)) {
            $stmt->bindParam(':category_id', $categoryId, PDO::PARAM_INT);
        }

        $stmt->execute();
        return $stmt;
    }


    // Format the product description (example utility method)
    public function formatDescription($description)
    {
        return strip_tags($description);
    }
}
