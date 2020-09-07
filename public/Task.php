<?php

class Task
{
  public function __construct($name, $email, $text, $complete = false, $edit = false)
  {
    $this->name = $name;
    $this->email = $email;
    $this->text = $text;
    $this->complete = $complete;
    $this->edit = $edit;
  }

  public static function getList($page = 0, $count = 3)
  {
    $db = new SQLite3("../tasks.sqlite");
    $query = "SELECT * FROM Tasks LIMIT :count OFFSET :offset";
    $query = $db->prepare($query);
    $query->bindValue(':offset', $page * $count, SQLITE3_INTEGER);
    $query->bindValue(':count', $count, SQLITE3_INTEGER);

    $tasks = $query->execute();

    if(!$tasks) return 0;

    $response = [
      "tasks" => []
    ];

    while ($task = $tasks->fetchArray(1)) {
      array_push($response["tasks"], $task);
    }

    $tasks->finalize();

    $pageCount = $db->querySingle('SELECT COUNT(*) FROM Tasks');

    $response["pageCount"] = $pageCount / $count;

    return ($response);
  }

  public function create()
  {
    $db = new SQLite3("../tasks.sqlite");

    $query = "INSERT INTO Tasks (name, email, text)
    VALUES (:name, :email, :text)";

    $query = $db->prepare($query);
    $query->bindValue(':name', $this->name, SQLITE3_TEXT);
    $query->bindValue(':email', $this->email, SQLITE3_TEXT);
    $query->bindValue(':text', $this->text, SQLITE3_TEXT);

    $query->execute();
  }

  public static function update($id, $text, $complete){
    $db = new SQLite3("../tasks.sqlite");

    $query = "UPDATE Tasks SET text = :newText, complete = :newComplete WHERE id = :id";
    $query = $db->prepare($query);
    $query->bindValue(":newComplete", $complete, SQLITE3_INTEGER);
    $query->bindValue(":newText", $text, SQLITE3_TEXT);
    $query->bindValue(":id", $id, SQLITE3_INTEGER);

    $query->execute();
  }

  public static function getTask($id){
    $db = new SQLite3("../tasks.sqlite");

    $query = "SELECT * FROM Tasks WHERE id = :id";
    $query = $db->prepare($query);
    $query->bindValue(":id", $id, SQLITE3_INTEGER);

    $res = $query->execute()->fetchArray(1);

    return ($res);
  }
}
