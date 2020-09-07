<?php

require '../vendor/autoload.php';
include "Task.php";

$router = new \Klein\Klein();

$router->respond("GET", "/static/[*]", function ($req, $res) {
  return $res->file(__DIR__ . $req->pathname());
});

$router->respond("POST", "/login.php", function ($req, $res, $service) {
  $service->validateParam("login")->notNull();
  $service->validateParam("pass")->notNull();

  require("login.php");

  Login();

  $res->redirect("/");
});

$router->respond("GET", "/", function ($req, $res) {
  require("index.template.php");
});


$router->respond("GET", "/tasks", function ($req, $res, $service) {
  return (Task::getList($_GET["page"] ?? NULL, $_GET["count"] ?? NULL));
});

$router->respond("GET", "/tasks/[:task_id]", function ($req, $res) {
  if(!isset($req->task_id)) return;
  return json_encode(Task::getTask($req->task_id));
});

$router->respond("POST", "/tasks", function ($req, $res, $service) {
  $body = file_get_contents('php://input');
  $body = json_decode($body, true);
  switch ($body["action"]) {
    case "CREATE":
      if (
        !isset($body["name"], $body["email"], $body["text"]) ||
        !filter_var($body["email"], FILTER_VALIDATE_EMAIL)
      ) return;

      (new Task($body["name"], $body["email"], $body["text"]))->create();
      break;

    case "UPDATE":
      if (!isset($body["id"], $body["text"], $body["complete"], $_COOKIE["admin"])) return;
      Task::update($body["id"], $body["text"], $body["complete"]);
      break;
    
    default:
      return;
  }
});

$router->dispatch();
