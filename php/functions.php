<?php
  function create_pdo() {
    return new PDO('mysql:host=localhost;dbname=d8abase;charset=utf8', 'root', '');
  }


?>
