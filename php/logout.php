<?php
  session_start();
  include 'functions.php';

  session_unset();
  session_destroy();
?>
