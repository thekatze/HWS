<?php
  session_start();

  include 'functions.php';

  if (isset($_SESSION)) {
    echo $_SESSION['userid'];
    echo $_SESSION['login'];
  } else {
    echo 'Error';
  }

?>
