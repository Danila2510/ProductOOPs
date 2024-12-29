<?php
class Product {
    public $name;
    public $price;

    public function __construct($name, $price) {
        $this->name = $name;
        $this->price = $price;
    }

    public function getProduct() {
        return "Name: {$this->name}; Price: {$this->price}";
    }
}
