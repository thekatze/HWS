<?php
  session_start();

  include 'functions.php';
  $pdo = create_pdo();

  if (isset($_SESSION['login']) && $_SESSION['login'] == 1) {
    if (isset($_POST['btn-upload']) && $_FILES['file']['size'] > 0 && isset($_FILES['file']['name'])) {
      if ($_FILES['file']['size'] < 8388608) {
        try {
          $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
          $pdo->beginTransaction();
          $f_name = $_FILES['file']['name'];
          $f_tmpname = $_FILES['file']['tmp_name'];
          $f_type = $_FILES['file']['type'];
          $f_size = $_FILES['file']['size'];
          $f_error = $_FILES['file']['error'];

          $u_respect = $_POST['respect'];
          $u_dollaz = $_POST['dollaz'];
          $u_description = $_POST['description'];
          $u_homework_id = $_POST['homework_id'];

          $fp      = fopen($f_tmpname, 'r');
          $content = fread($fp, filesize($f_tmpname));
          fclose($fp);

          $get_homework_stmt = $pdo->prepare("call get_homework_id(:id_in)");
          $get_homework_stmt->bindParam(':id_in', $u_homework_id);
          $get_homework_stmt->execute();
          $homeworkdata = $get_homework_stmt->fetch();
          $get_homework_stmt->closeCursor();

          if ($homeworkdata != "") {
            $insert_upload_stmt = $pdo->prepare("call insert_upload_with_file(:user_in, :respect_in, :dollaz_in, :description_in, :homework_in, :file_name, :file_type, :file_size, :file_data, @id_out)");
            $insert_upload_stmt->bindParam(':user_in', $_SESSION['userid']);
            $insert_upload_stmt->bindParam(':respect_in', $u_respect);
            $insert_upload_stmt->bindParam(':dollaz_in', $u_dollaz);
            $insert_upload_stmt->bindParam(':description_in', $u_description);
            $insert_upload_stmt->bindParam(':homework_in', $u_homework_id);
            $insert_upload_stmt->bindParam(':file_name', $f_name);
            $insert_upload_stmt->bindParam(':file_type', $f_type);
            $insert_upload_stmt->bindParam(':file_size', $f_size);
            $insert_upload_stmt->bindParam(':file_data', $content);

            $insert_upload_stmt->execute();
            echo $f_name." was successfully uploaded with the id: ".$pdo->query("select @id_out")->fetch()[0];

            $insert_upload_stmt->closeCursor();
          } else {
            echo "This homework doesnt exist";
          }
          $pdo->commit();
          //YAY hat geklappt
        } catch (Exception $e) {
          $pdo->rollBack();
          echo $e;
          echo "SQL Error \n";
        }
      } else {
        echo "File too big";
      }
    } elseif (isset($_FILES['file']['name']) && $_FILES['file']['name'] != "") {
      echo "Error with the File upload (Probably to big)";
    } else {
      echo "Kein File";
    }
  } else {
    echo "Not Logged in";
  }



/*
  if (isset($_SESSION['login']) && $_SESSION['login'] == 1) {
    if (isset($_POST['btn-upload']) && $_FILES['file']['size'] > 0) {
      if ($_FILES['file']['size'] < 8388608) {
        try {
          $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
          $pdo->beginTransaction();

              $f_name = $_FILES['file']['name'];
              $f_tmpname = $_FILES['file']['tmp_name'];
              $f_type = $_FILES['file']['type'];
              $f_size = $_FILES['file']['size'];
              $f_error = $_FILES['file']['error'];

              $fp      = fopen($f_tmpname, 'r');
              $content = fread($fp, filesize($f_tmpname));
              fclose($fp);

              $insert_file_stmt = $pdo->prepare("call insert_file(:name_in, :type_in, :size_in, :data_in, @a)");
              $insert_file_stmt->bindParam(':name_in', $f_name);
              $insert_file_stmt->bindParam(':type_in', $f_type);
              $insert_file_stmt->bindParam(':size_in', $f_size);
              $insert_file_stmt->bindParam(':data_in', $content);

              $insert_file_stmt->execute();
              echo $f_name." was successfully uploaded with the id: ".$pdo->query("select @a")->fetch()[0];

              $insert_file_stmt->closeCursor();


          $pdo->commit();
          //YAY hat geklappt
        } catch (Exception $e) {
          $pdo->rollBack();
          echo $e;
          echo "SQL Error \n";
        }
      } else {
        echo 'File is over the limit of 8MB';
      }
    } else {
      echo 'File not found';
    }
  } else {
    echo 'Not Logged in';
  }
*/
?>
