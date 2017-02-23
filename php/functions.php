<?php
  function create_pdo() {
    return new PDO('mysql:host=localhost;dbname=d8abase;charset=utf8', 'root', '');
  }

  function logout_user() {
    if (isset($_SESSION)) {
      session_unset();
      session_destroy();
    }
  }
?>
