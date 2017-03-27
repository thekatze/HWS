<?php

  include 'functions.php';

  $pdo = create_pdo();

  try {
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->beginTransaction();

    $get_user_stmt = $pdo->prepare("call insert_user(:username_in, :email_in, :passwd_in, :token_in, @a)");
    $get_user_stmt->bindParam(':username_in', $username);
    $get_user_stmt->bindParam(':email_in', $email);
    $get_user_stmt->bindParam(':passwd_in', $passwd);
    $get_user_stmt->bindParam(':token_in', $token);

    $username = $_GET['username'];
    $email = $_GET['email'];
    $passwd = password_hash($_GET['passwd'], PASSWORD_DEFAULT);
    $token = md5(uniqid(rand()));

    $get_user_stmt->execute();
    echo $pdo->query("select @a")->fetch()[0];

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
    //YAY hat geklappt
  } catch (Exception $e) {
    $pdo->rollBack();
  }

?>
