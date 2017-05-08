<?php

  include 'functions.php';
  include 'constants.php';

  $pdo = create_pdo();

  try {
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->beginTransaction();

    $post_data = file_get_contents("php://input");
    $post_username = json_decode($post_data)->{'u'};
    $post_password = json_decode($post_data)->{'pw'};
    $post_email = json_decode($post_data)->{'e'};

    $get_user_stmt = $pdo->prepare("call insert_user(:username_in, :email_in, :passwd_in, :token_in, @a)");
    $get_user_stmt->bindParam(':username_in', $post_username);
    $get_user_stmt->bindParam(':email_in', $post_email);
    $get_user_stmt->bindParam(':passwd_in', $passwd);
    $get_user_stmt->bindParam(':token_in', $token);

    $passwd = password_hash($post_password, PASSWORD_DEFAULT);
    $token = md5(uniqid(rand()));

    $get_user_stmt->execute();

    $get_user_stmt->closeCursor();


/*
    require_once('mailer/class.phpmailer.php');
      $mail = new PHPMailer();
      $mail->IsSMTP();
      $mail->SMTPDebug  = 0;
      $mail->SMTPAuth   = true;
      $mail->SMTPSecure = "ssl";
      $mail->Host       = "smtp.gmail.com";
      $mail->Port       = 465;
      $mail->Username="yourgmailid@gmail.com";
      $mail->Password="yourgmailpassword";

      $mail->AddAddress($email);
      $mail->SetFrom('you@yourdomain.com','Coding Cage');
      $mail->AddReplyTo("you@yourdomain.com","Coding Cage");
      $mail->Subject    = $subject;
      $mail->MsgHTML($message);
      $mail->Send();
*/
    $pdo->commit();
    $response = array('response' => SUCCESS);
    //YAY hat geklappt
  } catch (Exception $e) {
    $pdo->rollBack();
    $response = array('response' => SQL_FAIL);
  }
  echo json_encode($response);
?>
