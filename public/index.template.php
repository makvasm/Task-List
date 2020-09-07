<?php
$page = $_GET["page"] ?? 0;
$pageCount = $_GET["count"] ?? 3;
$tasks = Task::getList($page, $pageCount);
?>

<!DOCTYPE html>
<html lang="ru">

<head>
  <meta charset="UTF-8">
  <base href="/">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  <title>Tasks List</title>
</head>

<body>
  <?php
  if (!isset($_COOKIE["admin"])) {
    echo ('<div id="loginform-collapse" class="container">
    <a class="btn btn-primary" data-toggle="collapse" href="#loginform" role="button" aria-expanded="false"
    aria-controls="collapseExample">
    Login
    </a>
    <form action="/login.php" method="POST" id="loginform" class="collapse">
    <div class="form-group">
      <label for="exampleInputEmail1">Login</label>
      <input required type="text" class="form-control" id="exampleInputEmail1" aria-describedby="loginHelp"
      name="login">
    </div>
    <div class="form-group">
      <label for="exampleInputPassword1">Password</label>
      <input required type="password" class="form-control" id="exampleInputPassword1" name="pass">
    </div>
    <button type="submit" class="btn btn-primary">Submit</button>
    </form>
    </div>');
  }
  ?>

  <div class="container">
    <!-- Список задач -->
    <table class="table table-hover" id="tasks-list">
      <thead>
        <tr>
          <th scope="col">Author</th>
          <th scope="col">Email</th>
          <th scope="col">Task</th>
          <th scope="col">Complete</th>
        </tr>
      </thead>
      <tbody>
        <?php
        // Задачи
        if (isset($tasks["tasks"]) && sizeof($tasks["tasks"])) {
          foreach ($tasks["tasks"] as $task) {
            foreach ($task as $key => $value) {
              $task[$key] = htmlspecialchars($value);
            }

            $complete = "<input type='checkbox' %s %s />";
            $complete = sprintf($complete, $task["complete"] ? 'checked' : '', isset($_COOKIE["admin"]) ? "" : "disabled");

            $edit = $task["edit"] ? "<i>Отредактировано администратором</i>" : NULL;

            $text = isset($_COOKIE["admin"]) ? "<textarea>{$task["text"]}</textarea>" : $task["text"];

            print("
              <tr data-task-id={$task['id']} class='task'>
                <td class='task-name'>{$task["name"]}</td>
                <td class='task-email'>{$task["email"]}</td>
                <td class='task-text'>{$text}</td>
                <td class='task-complete'>{$complete} {$edit}</td>
              </tr>");
          }
        }
        ?>
      </tbody>
    </table>

    <!-- Навигация по страницам -->
    <nav aria-label="Page navigation">
      <ul class="pagination justify-content-center">
        <?php
        // Страницы
        if (isset($tasks) && $tasks["pageCount"] > 0) {
          for ($_page = 0; $_page < $tasks["pageCount"]; $_page++) {
            echo (
              sprintf(
                // Подстановка ссылок на страницы
                "<li class='page-item %s'><a class='page-link' href='/?page=%d&count=%s'>%d</a></li>",
                $_page == $page ? "active" : "",
                $_page,
                $pageCount,
                $_page + 1
              )
            );
          }
        }
        ?>
      </ul>
    </nav>

    <!-- Форма создания задачи -->
    <form id="createTaskForm">
      <div class="form-group">
        <label for="taskAuthorName">Name</label>
        <input required name="name" type="text" class="form-control" id="taskAuthorName" aria-describedby="emailHelp" placeholder="Name">
      </div>
      <div class="form-group">
        <label for="taskAuthorEmail">Email address</label>
        <input required name="email" type="email" class="form-control" id="taskAuthorEmail" aria-describedby="emailHelp" placeholder="any@example.com">
      </div>
      <div class="form-group">
        <label for="taskText">Text</label>
        <textarea required name="text" type="text" class="form-control" id="taskText" placeholder="Text..."></textarea>
      </div>
      <button type="submit" class="btn btn-primary">Create</button>
    </form>

  </div>

  <script src="./static/index.js"></script>
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>

</html>