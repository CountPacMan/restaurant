<?php

  /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */

  require_once "src/Restaurant.php";
  require_once "src/Cuisine.php";

  $DB = new PDO('pgsql:host=localhost;dbname=restaurant_test; password=password');

  /*  Spec 1. User runs getName, app returns restaurant name.
      Spec 2. User runs getCuisineId, app returns cuisine id for requested object.
      Spec 3. Database saves new entries.
      Spec 4. User requests a restaurant by id, app returns restaurant object.

  */

  class RestaurantTest extends PHPUnit_Framework_TestCase {

    protected function tearDown() {
      Restaurant::deleteAll();
      Cuisine::deleteAll();
    }

    function test_getName() {
      // Arrange
      $name = "Moony Eatz";
      $test_restaurant = new Restaurant($name);

      // Act
      $result = $test_restaurant->getName();

      // Assert
      $this->assertEquals($name, $result);
    }

    function test_getCuisineId() {
      // Arrange
      $name = "Moony Eatz";
      $cuisine_id = 1;
      $test_restaurant = new Restaurant($name, $cuisine_id);

      // Act
      $result = $test_restaurant->getCuisineId();

      // Assert
      $this->assertEquals($cuisine_id, $result);
    }

    function test_save() {
      // Arrange
      $name = "Moony Eatz";
      $test_restaurant = new Restaurant($name, 1);

      // Act
      $test_restaurant->save();

      // Assert
      $result = Restaurant::getAll();
      $this->assertEquals($test_restaurant, $result[0]);
    }

    function test_find() {
      // Arrange
      $name = "Moon Eatz";
      $cuisine_id = 2;
      $test_restaurant = new Restaurant($name, $cuisine_id);

      // Act
      $test_restaurant->save();
      $result = Restaurant::find($test_restaurant->getId());

      // Assert
      $this->assertEquals($test_restaurant, $result);
    }


  }
?>
