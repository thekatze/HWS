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
      $userid = 4;
      $update_classes_stmnt = $pdo->prepare("call get_classes_info(:userid)");
      $update_classes_stmnt->bindParam(':userid', $_SESSION['userid']);

      $update_classes_stmnt->execute();
      $data = $update_classes_stmnt->fetchAll();

      $update_classes_stmnt->closeCursor();

      $workedData = array();
      for ($i=0; $i < count($data); $i++) {
        $workedData[$i]['id'] = intval($data[$i]['classId']);
        $workedData[$i]['name'] = $data[$i]['className'];
        if ($data[$i]['rep']) {
            $workedData[$i]['status'] = REP;
        } elseif ($data[$i]['accepted']) {
            $workedData[$i]['status'] = YES;
        } else {
            $workedData[$i]['status'] = INV;
        }
      }

      $response = array(
        'response' => SUCCESS,
        'classes' => $workedData
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
