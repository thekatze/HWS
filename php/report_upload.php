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

      $report_upload_stmt = $pdo->prepare("call insert_upload_report(:userid, :uploadid, :note)");
      $report_upload_stmt->bindParam(':userid', $_SESSION['userid']);
      $report_upload_stmt->bindParam(':uploadid', $_SESSION['userid']);
      $report_upload_stmt->bindParam(':note', $_SESSION['userid']);

      $report_upload_stmt->execute();

      $report_upload_stmt->closeCursor();

      $response = array(
        'response' => SUCCESS
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
