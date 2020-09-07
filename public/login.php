<?php

function Login()
{
  require("../config.php");

  if (!isset($_COOKIE["admin"])) {
    if ($_POST["login"] == $CONFIG["login"]) {
      if (
        hash("sha256", $_POST["login"] . $_POST["pass"] . $CONFIG["secret"]) == hash("sha256", $CONFIG["login"] . $CONFIG["pass"] . $CONFIG["secret"])
      ) {
        setcookie("admin", "true",[
          "expire" => time() + 60 * 60 * 24 * 365 * 10,
          "httponly" => true
        ]);
      }
    }
  }
}
