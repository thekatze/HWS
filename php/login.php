<?php
  session_start();
  include 'functions.php';
  include 'constants.php';

  //Creating a connection to the Database
  $pdo = create_pdo();

  try {
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->beginTransaction();

    //Getting the login-data form the post
    $post_data = file_get_contents("php://input");
    $post_username = json_decode($post_data)->{'u'};
    $post_password = json_decode($post_data)->{'pw'};

    //Prepared Statement for the SQL procedure
    $get_user_stmt = $pdo->prepare("call get_user_username(:username)");
    $get_user_stmt->bindParam(':username', $post_username);

    $get_user_stmt->execute();
    $userdata = $get_user_stmt->fetch();

    $get_user_stmt->closeCursor();

    //Password verification
    if ($userdata['iduser'] != "" && password_verify($post_password, $userdata['password'])) {
      $session_id = session_id();
      //Checking of user is already logged in
      if (isset($userdata['session_id']) && $userdata['session_id'] != $session_id) {
        session_id($userdata['session_id']);
        logout_user();
        session_id($session_id);
      }
      //Setting Session varibles
      $_SESSION['userid'] = $userdata['iduser'];
      $_SESSION['login'] = 1;
      setcookie("cookiezi", $_SESSION['login'], 0, "/");
      //Update the stored Session id
      $update_user_stmt = $pdo->prepare("call update_user_session_id(:id_in, :session_id_in)");
      $update_user_stmt->bindParam(':id_in', $userdata['iduser']);
      $update_user_stmt->bindParam(':session_id_in', $session_id);

      $update_user_stmt->execute();
      $update_user_stmt->closeCursor();
      //Return value on Success
      $response = array('response' => SUCCESS);
    } else {
      //Return value on false password verification
      $response = array('response' => FAIL);
    }
    $pdo->commit();
  } catch (Exception $e) {
    //On SQL Error
    $pdo->rollBack();
    $response = array('response' => SQL_FAIL);
  }
  //Sending the return value to the js
  echo json_encode($response);
?>
