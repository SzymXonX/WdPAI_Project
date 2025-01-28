<?php

class Expense {
    private $id;
    private $userId;
    private $amount;
    private $categoryId;
    private $description;
    private $date;

    public function __construct($id, $userId, $amount, $categoryId, $description, $date) {
        $this->id = $id;
        $this->userId = $userId;
        $this->amount = $amount;
        $this->categoryId = $categoryId;
        $this->description = $description;
        $this->date = $date;
    }

    public function getId() {
        return $this->id;
    }

    public function getUserId() {
        return $this->userId;
    }

    public function getAmount() {
        return $this->amount;
    }

    public function getCategoryId() {
        return $this->categoryId;
    }

    public function getDescription() {
        return $this->description;
    }

    public function getDate() {
        return $this->date;
    }

    public function setUserId($userId) {
        $this->userId = $userId;
    }

    public function setAmount($amount) {
        $this->amount = $amount;
    }

    public function setCategoryId($categoryId) {
        $this->categoryId = $categoryId;
    }

    public function setDescription($description) {
        $this->description = $description;
    }

    public function setDate($date) {
        $this->date = $date;
    }
}
?>
