<?php
  session_start();
  include 'functions.php';
  include 'constants.php';

  //Creating a connection to the Database
  $pdo = create_pdo();

  //Controll if the user is still logged in
  if (isset($_SESSION['login']) && $_SESSION['login'] == 1) {
    try {
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $pdo->beginTransaction();

      $update_homework_stmt = $pdo->prepare("call get_homework_info(:userid)");
      $update_homework_stmt->bindParam(':userid', $_SESSION['userid']);

      $update_homework_stmt->execute();
      $data = $update_homework_stmt->fetchAll();

      //Clearing the SQL Array from some thing i dont need
      $workedData = array();
      for ($i=0; $i < count($data); $i++) {
        $workedData[$i]['id'] = intval($data[$i]['homeworkId']);
        $workedData[$i]['name'] = $data[$i]['homeworkName'];
        $workedData[$i]['class'] = $data[$i]['homeworkClass'];
        $workedData[$i]['date'] = $data[$i]['homeworkDate'];
      }

      $update_homework_stmt->closeCursor();
      //Creating the Success response
      $response = array(
        'response' => SUCCESS,
        'homeworks' => $workedData
      );

      $pdo->commit();
    } catch (Exception $e) {
      //Response if there is a SQL Error
      $pdo->rollBack();
      $response = array('response' => SQL_FAIL);
    }
  } else {
    //Response if user is not logged in
    $response = array('response' => NOT_LOGGED_IN);
  }
  echo json_encode($response);
?>
