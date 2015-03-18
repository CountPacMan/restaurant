<?php
  require_once __DIR__."/../vendor/autoload.php";
  require_once __DIR__."/../src/Restaurant.php";
  require_once __DIR__."/../src/Cuisine.php";

  $app = new Silex\Application();

  $DB = new PDO('pgsql:host=localhost;dbname=restaurant;password=password');

  $app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/../views'
  ));

  use Symfony\Component\HttpFoundation\Request;
  Request::enableHttpMethodParameterOverride();

  $app->get("/", function() use ($app) {
    return $app['twig']->render('index.html.twig', array('cuisines' => Cuisine::getAllCuisines()));
  });

  $app->get("/cuisines/{id}", function($id) use ($app) {
    $cuisine = Cuisine::find($id);
    return $app['twig']->render('cuisine.html.twig', array('cuisine' => $cuisine, 'restaurants' => $cuisine->getRestaurants()));
  });

  $app->get("/cuisines/{id}/edit", function($id) use ($app) {
     $cuisine = Cuisine::find($id);
     return $app['twig']->render('cuisine_edit.html.twig', array('cuisine' => $cuisine));
  });

  $app->patch("/cuisines/{id}", function($id) use ($app) {
    $type = $_POST['type'];
    $cuisine = Cuisine::find($id);
    $cuisine->update($type);
    return $app['twig']->render('cuisine.html.twig', array('cuisine' => $cuisine, 'restaurants' => $cuisine->getRestaurants()));
  });

  $app->post("/cuisines", function() use ($app) {
    $cuisine = new Cuisine($_POST['type']);
    $cuisine->save();
    return $app['twig']->render('index.html.twig', array('cuisines' => Cuisine::getAllCuisines()));
  });

  $app->post("/restaurants", function() use ($app) {
    $restaurant = new Restaurant($_POST['name'], $_POST['cuisine_id']);
    $restaurant->save();
    $cuisine = Cuisine::find($_POST['cuisine_id']);
    return $app['twig']->render('cuisine.html.twig', array('cuisine' => $cuisine, 'restaurants' => $cuisine->getRestaurants()));
  });

  $app->post("/search", function() use ($app) {
    $results = Cuisine::search($_POST['name']);
    $temp = [];
    foreach($results as $result) {
      $temp_cuisine = Cuisine::find($result->getCuisineId());
      $type = $temp_cuisine->getType();
      array_push($temp, $type);
    }
    return $app['twig']->render('search_results.html.twig', array('results' => $temp, 'search_term' => $_POST['name']));
  });

  $app->post("/deleteRestaurants", function() use ($app) {
    Restaurant::deleteAll();
    return $app['twig']->render('index.html.twig');
  });

  $app->post("/deleteCategories", function() use ($app) {
    Cuisine::deleteAll();
    return $app['twig']->render('index.html.twig');
  });

  $app->delete("/cuisines/{id}", function($id) use ($app) {
    $cuisine = Cuisine::find($id);
    $cuisine->delete();
    return $app['twig']->render('index.html.twig', array('cuisines' => Cuisine::getAllCuisines()));
  });

  return $app;
?>
