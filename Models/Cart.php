<?php
session_start();

class Cart {
    public function __construct() {
        // Initialize the cart if it doesn't exist
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
    }

    public function addItem($product) {
        $_SESSION['cart'][$product->id] = $product;
    }

    public function removeItem($productId) {
        if (isset($_SESSION['cart'][$productId])) {
            unset($_SESSION['cart'][$productId]);
        }
    }

    public function getItems() {
        return $_SESSION['cart'];
    }

    public function calculateTotal() {
        $total = 0;
        foreach ($_SESSION['cart'] as $product) {
            $total += $product->price;
        }
        return $total;
    }
}
?>
