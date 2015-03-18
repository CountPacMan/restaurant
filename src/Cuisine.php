<?php
  class Cuisine {
    private $type;
    private $id;

    function __construct($type, $id = null)   {
      $this->type = $type;
      $this->id = $id;
    }

    // getters
    function getType()  {
      return $this->type;
    }

    function getId() {
      return $this->id;
    }

    // setters
    function setType($type)  {
      $this->type = (string) $type;
    }

    function setId($id) {
      $this->id = (int) $id;
    }

    // DB

    function save() {
      $statement = $GLOBALS['DB']->query("INSERT INTO cuisines (type) VALUES ('{$this->getType()}') RETURNING id;");
      $result = $statement->fetch(PDO::FETCH_ASSOC);
      $this->setId($result['id']);
    }

    function update($type) {
        $GLOBALS['DB']->exec("UPDATE cuisines SET type = '{$type}' WHERE id = {$this->getId()}");
        $this->setType($type);
    }

    function delete() {
        $GLOBALS['DB']->exec("DELETE FROM cuisines WHERE id = {$this->getId()};");
        $GLOBALS['DB']->exec("DELETE FROM restaurants WHERE cuisine_id = {$this->getId()};");
    }


    static function find($id) {
      $found_cuisines = null;
      $cuisines = Cuisine::getAllCuisines();
      foreach ($cuisines as $cuisine) {
        $cuisine_id = $cuisine->getId();
        if ($cuisine_id == $id) {
          $found_cuisines = $cuisine;
        }
      }
      return $found_cuisines;
    }


    static function getAllCuisines() {
      $returned_cuisines = $GLOBALS['DB']->query("SELECT * FROM cuisines ORDER BY type;");
      $cuisines = array();
      foreach($returned_cuisines as $cuisine) {
        $type = $cuisine['type'];
        $id = $cuisine['id'];
        $new_cuisine = new Cuisine($type, $id);
        array_push($cuisines, $new_cuisine);
      }
      return $cuisines;
    }

    function getRestaurants() {
      $returned_restaurants = $GLOBALS['DB']->query("SELECT * FROM restaurants WHERE cuisine_id = {$this->getId()};");
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

    static function search($name) {
    $restaurants = [];
    $returned_restaurants = $GLOBALS['DB']->query("SELECT * FROM restaurants WHERE name = '{$name}';");
    foreach ($returned_restaurants as $restaurant) {
      $new_Restaurant = new Restaurant($restaurant['name'], $restaurant['address'], $restaurant['phone'], $restaurant['cuisine_id'], $restaurant['id']);
      array_push($restaurants, $new_Restaurant);
    }
    return $restaurants;
  }

    static function deleteAll() {
      $GLOBALS['DB']->exec("DELETE FROM cuisines *;");
    }
  }
?>
