<?php
  session_start();

  include 'functions.php';


  $pdo = create_pdo();

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

?>
