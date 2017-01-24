<?php
  session_start();
  include 'functions.php';

  $pdo = create_pdo();

  try {
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->beginTransaction();

    $get_user_stmt = $pdo->prepare("call get_user_username(:username)");
    $get_user_stmt->bindParam(':username', $_GET['username']);

    $get_user_stmt->execute();
    $userdata = $get_user_stmt->fetch();

    if ($userdata['iduser'] != "" && password_verify($_GET['passwd'], $userdata['password'])) {
      echo "Wrks";
      $_SESSION['userid'] = $userdata['iduser'];
      echo session_id();
    } else {
      echo "Passwort oder Nutzername isch falsch";
      session_destroy();
    }

    $pdo->commit();
    //YAY hat geklappt
  } catch (Exception $e) {
    $pdo->rollBack();
    echo "wut";
    session_destroy();
  }
?>
