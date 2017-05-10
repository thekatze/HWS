<?php
  session_start();
  include 'functions.php';
  include 'constants.php';

  //Creating a connection to the Database
  $pdo = create_pdo();

  //Controll if the user is still logged in
  if (isset($_SESSION['login']) && $_SESSION['login'] == 1) {
    try {
        $post_data = file_get_contents("php://input");
        $post_class = json_decode($post_data)->{'c'};

      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $pdo->beginTransaction();

      $update_class_members_stmnt = $pdo->prepare("call get_class_members(:classid)");
      $update_class_members_stmnt->bindParam(':classid', $post_class);

      $update_class_members_stmnt->execute();
      $data = $update_class_members_stmnt->fetchAll();

      $update_class_members_stmnt->closeCursor();

      $workedData = array();
      for ($i=0; $i < count($data); $i++) {
        if ($data[$i]['rep']) {
            $status = "Represantetive";
        } elseif ($data[$i]['acc']) {
            $status = "Norm";
        } else {
            $status = "Invited";
        }
        $workedData[$i] = $data[$i]['username']." ".$status;
      }

      $response = array(
        'response' => SUCCESS,
        'class_members' => $workedData
      );

      $pdo->commit();
    } catch (Exception $e) {
      //Response if there is a SQL Error
      $pdo->rollBack();
      $response = array('response' => SQL_FAIL, 'error' => $e);
    }
  } else {
    //Response if user is not logged in
    $response = array('response' => NOT_LOGGED_IN);
  }
  echo json_encode($response);
?>
