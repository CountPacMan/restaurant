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
      $returned_cuisines = $GLOBALS['DB']->query("SELECT * FROM cuisines;");
      $cuisines = array();
      foreach($returned_cuisines as $cuisine) {
        $type = $cuisine['type'];
        $id = $cuisine['id'];
        $new_cuisine = new Cuisine($type, $id);
        array_push($cuisines, $new_cuisine);
      }
      return $cuisines;
    }

    static function getRestaurants() {
      $returned_restaurants = $GLOBALS['DB']->query("SELECT * FROM restaurants;");
      $restaurants = array();
      foreach($returned_restaurants as $restaurant) {
        $description = $restaurant['description'];
        $cuisine_id = $restaurant['category_id'];
        $id = $restaurant['id'];
        $new_restaurant = new Cuisine($name, $cuisine_id, $id, $due_date);
        array_push($restaurants, $new_restaurant);
      }
      return $restaurants;
    }

    static function search($name) {
    $restaurants = [];
    $returned_restaurants = $GLOBALS['DB']->query("SELECT * FROM restaurants WHERE name = '{$name}';");
    foreach ($returned_restaurants as $restaurant) {
      $new_Restaurant = new Restaurant($restaurant['name'], $restaurant['cuisine_id'], $restaurant['id']);
      array_push($restaurants, $new_Restaurant);
    }
    return $restaurants;
  }

    static function deleteAll() {
      $GLOBALS['DB']->exec("DELETE FROM cuisines *;");
    }
  }
?>
