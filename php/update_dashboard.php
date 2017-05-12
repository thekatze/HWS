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

      $update_dashboard_user_stmt = $pdo->prepare("call get_dashboard_info_user(:userid)");
      $update_dashboard_user_stmt->bindParam(':userid', $_SESSION['userid']);

      $update_dashboard_user_stmt->execute();
      $data = $update_dashboard_user_stmt->fetch();

      $update_dashboard_user_stmt->closeCursor();



      $update_dashboard_home_stmt = $pdo->prepare("call get_dashboard_info_homework(:userid)");
      $update_dashboard_home_stmt->bindParam(':userid', $_SESSION['userid']);

      $update_dashboard_home_stmt->execute();
      $data2 = $update_dashboard_home_stmt->fetch();

      $update_dashboard_home_stmt->closeCursor();

      $response = array(
        'response' => SUCCESS,
        'user' => array(
          'name' => $data['username'],
          'respect' => intval($data['respect']),
          'dollaz' => floatval($data['dollaz']),
          'openHomeworks' => intval($data2['homeworkCount'])
        ),
        'nextHomework' => array(
          'id' => $data2['homeworkId'],
          'name' => $data2['homeworkName'],
          'class' => $data2['homeworkClass'],
          'date' => $data2['homeworkDate']
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
