<?php

class Income {
    private $id;
    private $userId;
    private $amount;
    private $source;
    private $description;
    private $date;

    public function __construct($id, $userId, $amount, $source, $description, $date) {
        $this->id = $id;
        $this->userId = $userId;
        $this->amount = $amount;
        $this->source = $source;
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

    public function getSource() {
        return $this->source;
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

    public function setSource($source) {
        $this->source = $source;
    }

    public function setDescription($description) {
        $this->description = $description;
    }

    public function setDate($date) {
        $this->date = $date;
    }
}
?>
