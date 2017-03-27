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

      $update_dashboard_stmt = $pdo->prepare("call get_dashboard_info(:userid)");
      $update_dashboard_stmt->bindParam(':userid', $_SESSION['userid']);

      $update_dashboard_stmt->execute();
      $data = $update_dashboard_stmt->fetch();

      $update_dashboard_stmt->closeCursor();



      $response = array(
        'response' => SUCCESS,
        'user' => array(
          'name' => $data['username'],
          'respect' => intval($data['respect']),
          'dollaz' => floatval($data['dollaz']),
          'openHomeworks' => intval($data['homeworkCount'])
        ),
        'nextHomework' => array(
          'id' => $data['homeworkId'],
          'name' => $data['homeworkName'],
          'class' => $data['homeworkClass'],
          'date' => $data['homeworkDate']
        )
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
