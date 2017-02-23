<?php
  session_start();
  include 'functions.php';

  $pdo = create_pdo();

  try {
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->beginTransaction();

    $get_user_stmt = $pdo->prepare("call get_user_username(:username)");
    $get_user_stmt->bindParam(':username', $_POST['username']);

    $get_user_stmt->execute();
    $userdata = $get_user_stmt->fetch();

    $get_user_stmt->closeCursor();

    if ($userdata['iduser'] != "" && password_verify($_POST['password'], $userdata['password'])) {
      $session_id = session_id();
      if (isset($userdata['session_id']) && $userdata['session_id'] != $session_id) {
        session_id($userdata['session_id']);
        logout_user();
        session_id($session_id);
      }
      $_SESSION['userid'] = $userdata['iduser'];
      $_SESSION['login'] = 1;
      $update_user_stmt = $pdo->prepare("call update_user_session_id(:id_in, :session_id_in)");
      $update_user_stmt->bindParam(':id_in', $userdata['iduser']);
      $update_user_stmt->bindParam(':session_id_in', $session_id);

      $update_user_stmt->execute();
      $update_user_stmt->closeCursor();
      echo "successfully logged in ".$_SESSION['userid'].$session_id;
    } else {
      echo "Passwort oder Nutzername isch falsch";
    }

    $pdo->commit();
    //YAY hat geklappt
  } catch (Exception $e) {
    $pdo->rollBack();
    echo "SQL Error";
  }
?>
