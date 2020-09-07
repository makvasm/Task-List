<?php
$db = new SQLite3("../tasks.sqlite");

$db->exec("CREATE TRIGGER IF NOT EXISTS on_edit AFTER UPDATE ON Tasks
WHEN
  OLD.edit <> true
BEGIN
  UPDATE Tasks
  SET edit = true
  WHERE id = OLD.id;
END;");

$db->exec("CREATE TABLE IF NOT EXISTS Tasks ( 
  id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
  name TEXT NOT NULL,
  email TEXT NOT NULL,
  text TEXT NOT NULL,
  edit BOOLEAN DEFAULT false,
  complete BOOLEAN DEFAULT false)");