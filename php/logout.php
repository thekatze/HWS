<?php
  session_start();
  include 'functions.php';
  include 'constants.php';

  session_destroy();
  unset($_COOKIE['cookiezi']);
  $response = array('response' => SUCCESS);
  echo json_encode($response);
?>
