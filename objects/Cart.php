<?php
class Cart {
    private $db;

    // Constructor
    public function __construct($db) {
        $this->db = $db;
    }

    // Add an item to the cart
    public function addToCart($id, $quantity) {
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        if (!isset($_SESSION['cart'][$id])) {
            $_SESSION['cart'][$id] = 0;
        }

        $_SESSION['cart'][$id] += $quantity;
    }

    // Remove an item from the cart
    public function removeFromCart($id) {
        if (isset($_SESSION['cart'][$id])) {
            unset($_SESSION['cart'][$id]);
        }
    }

    // Update the quantity of an item in the cart
    public function updateCart($id, $quantity) {
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        if ($quantity <= 0) {
            unset($_SESSION['cart'][$id]);
        } else {
            $_SESSION['cart'][$id] = $quantity;
        }
    }
}
?>
