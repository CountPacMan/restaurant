<?php

  /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */

  require_once "src/Cuisine.php";

  $DB = new PDO('pgsql:host=localhost;dbname=restaurant_test');


  /*  Spec 1. User runs getType; app returns cuisine type.
      Spec 2. User runs getId; app returns id.
      Spec 3. Database saves entry.
      Spec 4. User requests a cuisine by id. Returns cuisine object.
  */

  class CategoryTest extends PHPUnit_Framework_TestCase {

    protected function tearDown() {
      // Restaurant::deleteAll();
      Cuisine::deleteAll();
    }

    // Spec 1
    function test_getType() {
      // Arrange
      $type = "Moon food";
      $test_Cuisine = new Cuisine($type);

      // Act
      $result = $test_Cuisine->getType();

      // Assert
      $this->assertEquals($type, $result);
    }

    // Spec 2
    function test_getId() {
      // Arrange
      $type = "Moon food";
      $test_Cuisine = new Cuisine($type, 1);

      // Act
      $result = $test_Cuisine->getId();

      // Assert
      $this->assertEquals(1, $result);
    }

    // Spec 3
    function test_save() {
      // Arrange
      $type = "Moon food";
      $test_Cuisine = new Cuisine($type);

      // Act
      $test_Cuisine->save();
      $result = Cuisine::getAllCuisines();

      // Assert
      $this->assertEquals($test_Cuisine, $result[0]);
    }

    // Spec 4
    function test_find() {
      // Arrange
      $type = "Moon food";
      $test_Cuisine = new Cuisine($type);
      $type2 = "Other foods";
      $test_Cuisine2 = new Cuisine($type2);

      // Act
      $test_Cuisine->save();
      $test_Cuisine2->save();
      $result = Cuisine::find($test_Cuisine->getId());

      // Assert
      $this->assertEquals($test_Cuisine, $result);
    }

  }
?>
