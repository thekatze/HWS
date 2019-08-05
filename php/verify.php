<?php
    include 'functions';

    $pdo = create_pdo();

    try {
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $pdo->beginTransaction();

      $get_user_stmt = $pdo->prepare("call get_user_token(:iduser_in)");
      $get_user_stmt->bindParam(':iduser_in', $iduser);

      $iduser = $_GET['id'];

      $get_user_stmt->execute();

      $token = $get_user_stmt->fetch()['token'];

      $get_user_stmt->closeCursor();

      if ($_GET['token'] == $token) {
          $get_user_stmt = $pdo->prepare("call update_user_status(:iduser_in)");
          $get_user_stmt->bindParam(':iduser_in', $iduser);

          $iduser = $_GET['id'];

          $get_user_stmt->execute();

          $get_user_stmt->closeCursor();
      }


      $pdo->commit();
      //YAY hat geklappt
    } catch (Exception $e) {
      $pdo->rollBack();
    }
?>
