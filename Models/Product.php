<?php
// File: Models/Product.php

class Product {
    public $id;
    public $name;
    public $price;
    public $description;

    public function __construct($id, $name, $price, $description) {
        $this->id = $id;
        $this->name = $name;
        $this->price = $price;
        $this->description = $description;
    }
}
?>
