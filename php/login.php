<?php
$pdo = create_pdo();


try {
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $pdo->beginTransaction();

  $get_user_stmt = $pdo->prepare("call insert_user(:username_in, :email_in, :passwd_in, @a)");
  $get_user_stmt->bindParam(':username_in', $username);
  $get_user_stmt->bindParam(':email_in', $email);
  $get_user_stmt->bindParam(':passwd_in', $passwd);

  for ($i = 0; $i < 10; $i++) {
    $username = "Penis nr. ".$i;
    $email = "schwonz".$i."@htlcockbirn.at";
    $passwd = $i;

    $get_user_stmt->execute();


    echo $pdo->query("select @a")->fetch()[0];

    $get_user_stmt->closeCursor();
  }

  $get_user_stmt->closeCursor();

  $pdo->commit();
  //YAY hat geklappt
} catch (Exception $e) {
  $pdo->rollBack();
}




function create_pdo() {
  return new PDO('mysql:host=localhost;dbname=d8base;charset=utf8', 'root', '');
}
?>
