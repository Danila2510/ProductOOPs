<?php
class Category {
    private $name;
    private $products;

    public function __construct($name, $products) {
        $this->name = $name;
        $this->products = $products;
    }

    public function getCategoryName() {
        return $this->name;
    }

    public function getCategoryProducts() {
        return $this->products;
    }

    public function addProduct($product) {
        $this->products[] = $product;
    }
}
