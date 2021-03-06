<?php
  class Restaurant {
    private $name;
    private $address;
    private $phone;
    private $cuisine_id;
    private $id;

    function __construct($name, $address, $phone, $cuisine_id = null, $id = null) {
      $this->name = $name;
      $this->address = $address;
      $this->phone = $phone;
      $this->cuisine_id = $cuisine_id;
      $this->id = $id;
    }

    // getters
    function getName()  {
      return $this->name;
    }

    function getAddress() {
      return $this->address;
    }

    function getPhone() {
      return $this->phone;
    }

    function getCuisineId() {
      return $this->cuisine_id;
    }

    function getId() {
      return $this->id;
    }

    // setters
    function setName($name)  {
      $this->name = (string) $name;
    }

    function setAddress($address) {
      $this->address = $address;
    }

    function setPhone($phone) {
      $this->phone = $phone;
    }

    function setCuisineId($cuisine_id) {
      $this->cuisine_id = (int) $cuisine_id;
    }

    function setId($id) {
      $this->id = (int) $id;
    }

    // // DB

    function save() {
      $statement = $GLOBALS['DB']->query("INSERT INTO restaurants (name, cuisine_id, address, phone) VALUES ('{$this->getName()}', {$this->getCuisineId()}, '{$this->getAddress()}', '{$this->getPhone()}') RETURNING id;");
      $result = $statement->fetch(PDO::FETCH_ASSOC);
      $this->setId($result['id']);
    }

    static function find($search_id) {
      $found_restaurant = null;
      $restaurants = Restaurant::getAll();
      foreach ($restaurants as $restaurant) {
        $restaurant_id = $restaurant->getId();
        if ($restaurant_id == $search_id) {
          $found_restaurant = $restaurant;
        }
      }
      return $found_restaurant;
    }

    static function getAll() {
      $returned_restaurants = $GLOBALS['DB']->query("SELECT * FROM restaurants;");
      $restaurants = array();
      foreach($returned_restaurants as $restaurant) {
        $name = $restaurant['name'];
        $address = $restaurant['address'];
        $phone = $restaurant['phone'];
        $cuisine_id = $restaurant['cuisine_id'];
        $id = $restaurant['id'];
        $new_restaurant = new Restaurant($name, $address, $phone, $cuisine_id, $id);
        array_push($restaurants, $new_restaurant);
      }
      return $restaurants;
    }

    static function deleteAll() {
      $GLOBALS['DB']->exec("DELETE FROM restaurants *;");
    }
  }
?>
