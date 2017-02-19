<?php

  include 'functions.php';

  $pdo = create_pdo();

  try {
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->beginTransaction();

    $get_user_stmt = $pdo->prepare("call insert_user(:username_in, :email_in, :passwd_in, @a)");
    $get_user_stmt->bindParam(':username_in', $username);
    $get_user_stmt->bindParam(':email_in', $email);
    $get_user_stmt->bindParam(':passwd_in', $passwd);

    $username = $_GET['username'];
    $email = $_GET['email'];
    $passwd = password_hash($_GET['passwd'], PASSWORD_DEFAULT);

    $get_user_stmt->execute();
    echo $pdo->query("select @a")->fetch()[0];

    $get_user_stmt->closeCursor();

    $pdo->commit();
    //YAY hat geklappt
  } catch (Exception $e) {
    $pdo->rollBack();
  }

?>
