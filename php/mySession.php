<?php
  session_start();

  include 'functions.php';

  if (isset($_SESSION)) {
    echo $_SESSION['userid'];
  } else {
    echo 'Error';
  }

?>
