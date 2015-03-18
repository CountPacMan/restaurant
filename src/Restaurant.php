<?php
  class Restaurant {
    private $name;
    private $cuisine_id;
    private $id;

    function __construct($name, $cuisine_id = null, $id = null) {
      $this->name = $name;
      $this->cuisine_id = $cuisine_id;
      $this->id = $id;
    }

    // getters
    function getName()  {
      return $this->name;
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

    function setCuisineId($cuisine_id) {
      $this->cuisine_id = (int) $cuisine_id;
    }

    function setId($id) {
      $this->id = (int) $id;
    }

    // // DB

    function save() {
      $statement = $GLOBALS['DB']->query("INSERT INTO restaurants (name, cuisine_id) VALUES ('{$this->getName()}', {$this->getCuisineId()}) RETURNING id;");
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
        $cuisine_id = $restaurant['cuisine_id'];
        $id = $restaurant['id'];
        $new_restaurant = new Restaurant($name, $cuisine_id, $id);
        array_push($restaurants, $new_restaurant);
      }
      return $restaurants;
    }

    static function deleteAll() {
      $GLOBALS['DB']->exec("DELETE FROM restaurants *;");
    }
  }
?>
