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
      $statement = $GLOBALS['DB']->query("INSERT INTO tasks (description, category_id, due_date) VALUES ('{$this->getDescription()}', {$this->getCategoryId()}, '{$this->getDueDate()}') RETURNING id;");
      $result = $statement->fetch(PDO::FETCH_ASSOC);
      $this->setId($result['id']);
    }

    static function find($search_id) {
      $found_task = null;
      $tasks = Cuisine::getAll();
      foreach ($tasks as $task) {
        $task_id = $task->getId();
        if ($task_id == $search_id) {
          $found_task = $task;
        }
      }
      return $found_task;
    }

    static function getAll() {
      $returned_tasks = $GLOBALS['DB']->query("SELECT * FROM tasks;");
      $tasks = array();
      foreach($returned_tasks as $task) {
        $description = $task['description'];
        $category_id = $task['category_id'];
        $id = $task['id'];
        $due_date = $task['due_date'];
        $due_date = str_replace("-", "/", $due_date);
        $new_task = new Cuisine($description, $category_id, $id, $due_date);
        array_push($tasks, $new_task);
      }
      return $tasks;
    }

    static function deleteAll() {
      $GLOBALS['DB']->exec("DELETE FROM tasks *;");
    }
  }
?>
