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
        $post_user = json_decode($post_data)->{'u'};


      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $pdo->beginTransaction();

      $insert_acceptance_stmt = $pdo->prepare("call 	insert_class_user_invite(:userid, :classid)");
      $insert_acceptance_stmt->bindParam(':userid', $post_user);
      $insert_acceptance_stmt->bindParam(':classid', $post_class);

      $insert_acceptance_stmt->execute();

      $insert_acceptance_stmt->closeCursor();

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
