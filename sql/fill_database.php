<?php

  include $_SERVER['DOCUMENT_ROOT'].'/HWS/php/functions.php';
  include $_SERVER['DOCUMENT_ROOT'].'/HWS/php/constants.php';

  $pdo = create_pdo();

  $date = new DateTime('now');
  $date->add(new DateInterval('P10D'));

  try {
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->beginTransaction();

    $insert_user_stmt = $pdo->prepare("call insert_user(:username_in, :email_in, :passwd_in, @id_out)");
    $insert_user_stmt->bindParam(':username_in', $username);
    $insert_user_stmt->bindParam(':email_in', $email);
    $insert_user_stmt->bindParam(':passwd_in', $passwd);

    $user_ids = array();

    for ($i=0; $i < 10; $i++) {
      $username = "testUser".$i;
      $email = "test".$i."@test.com";
      $passwd = password_hash("pass", PASSWORD_DEFAULT);
      $insert_user_stmt->execute();
      array_push($user_ids, $pdo->query("select @id_out")->fetch()[0]);
      echo "Username: ".$username." Email: ".$email." Password: pass UserId: ".$user_ids[$i]."</br>";
    }

    $insert_user_stmt->closeCursor();
    echo "</br>";

    $insert_class_stmt = $pdo->prepare("call insert_class(:classname_in, :iduser_in, @id_out)");
    $insert_class_stmt->bindParam(':classname_in', $classname);
    $insert_class_stmt->bindParam(':iduser_in', $rep_id);

    $class_ids = array();

    for ($i=0; $i < 2; $i++) {
      $classname = "testClass".$i;
      $rep_id = $user_ids[5*$i];
      $insert_class_stmt->execute();
      array_push($class_ids, $pdo->query("select @id_out")->fetch()[0]);
      echo "Classname: ".$classname." Classrep_id: ".$rep_id." Classid: ".$class_ids[$i]."</br>";
    }

    $insert_class_stmt->closeCursor();
    echo "</br>";

    $insert_homework_stmt = $pdo->prepare("call insert_homework(:idclass_in, :date_in, :name_in, @id_out)");
    $insert_homework_stmt->bindParam(':idclass_in', $class_id);
    $insert_homework_stmt->bindParam(':date_in', $date_in);
    $insert_homework_stmt->bindParam(':name_in', $homeworkname);

    $homeworkgroup_ids = array();
    for ($j=0; $j < 2; $j++) {
      $homeworkgroup_ids[$j] = array();
      for ($i=0; $i < 3; $i++) {
        $class_id = $class_ids[$j];
        $date_in = $date->format('Y-m-d H:i:s');
        $homeworkname = "homework".$i;
        $insert_homework_stmt->execute();
        array_push($homeworkgroup_ids[$j], $pdo->query("select @id_out")->fetch()[0]);
        echo "HomeworkName: ".$homeworkname." date: ".$date_in." Classid: ".$class_id." Homeworkid: ".$homeworkgroup_ids[$j][$i]."</br>";
      }
    }
    $insert_homework_stmt->closeCursor();
    echo "</br>";


    $pdo->commit();
    //YAY hat geklappt
  } catch (Exception $e) {
    $pdo->rollBack();
    echo $e;
  }

?>
