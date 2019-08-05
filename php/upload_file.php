<?php
  session_start();

  include 'functions.php';
  include 'constants.php';
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


          $insert_upload_stmt = $pdo->prepare("call insert_upload_with_file(:userid, :respect, :dollaz, :description, :homeworkid, :filename, :filetype, :filesize, :filedata, @id_out)");
          $insert_upload_stmt->bindParam(':userid', $_SESSION['userid']);
          $insert_upload_stmt->bindParam(':respect', $_POST['respect']);
          $insert_upload_stmt->bindParam(':dollaz', $_POST['dollaz']);
          $insert_upload_stmt->bindParam(':description', $_POST['description']);
          $insert_upload_stmt->bindParam(':homeworkid', $_POST['homework']);
          $insert_upload_stmt->bindParam(':filename', $f_name);
          $insert_upload_stmt->bindParam(':filetype', $f_type);
          $insert_upload_stmt->bindParam(':filesize', $f_size);
          $insert_upload_stmt->bindParam(':filedata', $content);
          $insert_upload_stmt->execute();

          $insert_upload_stmt->closeCursor();
          $pdo->commit();
          $response = array('response' => SUCCESS);
        } catch (Exception $e) {
          $pdo->rollBack();
          $response = array('response' => SQL_FAIL, 'error' => $e);
        }
      } else {
        $response = array('response' => FAIL, 'error' => 'File bigger then 8MB');
      }
    } else {
      $response = array('response' => FAIL, 'error' => 'File not found');
    }
  } else {
    $response = array('response' => NOT_LOGGED_IN);
  }
  echo json_encode($response);
  //$server = $_SERVER['HTTP_HOST']
  //echo $server
  header("Location: ../#/app/homework");
?>
