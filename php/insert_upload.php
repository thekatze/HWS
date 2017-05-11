<?php
  session_start();

  include 'functions.php';
  include 'constants.php';
  $pdo = create_pdo();

  if (isset($_SESSION['login']) && $_SESSION['login'] == 1) {
      $post_data = file_get_contents("php://input");
      $post_description = json_decode($post_data)->{'n'};
      $post_respect = json_decode($post_data)->{'r'};
      $post_dollaz = json_decode($post_data)->{'d'};
      $post_homework = json_decode($post_data)->{'h'};
      $file_name = json_decode($post_data)->{'f_name'};
      $file_size = json_decode($post_data)->{'f_size'};
      $file_type = json_decode($post_data)->{'f_type'};
      $file_rawData = json_decode($post_data)->{'f_data'};

    if ($file_size > 0 && isset($file_name)) {
      if ($file_size < 8388608) {
        try {
          $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
          $pdo->beginTransaction();
          /*
          $fp      = fopen($file_tmp, 'r');
          $content = fread($fp, filesize($file_tmp));
          fclose($fp);
          */
          $exist_homework_stmt = $pdo->prepare("call exist_homework_id(:id_in)");
          $exist_homework_stmt->bindParam(':id_in', $post_homework);
          $exist_homework_stmt->execute();
          $homeworkdata = $exist_homework_stmt->fetch();
          $exist_homework_stmt->closeCursor();

          if ($homeworkdata['response'] == 1) {
            $insert_upload_stmt = $pdo->prepare("call insert_upload_with_file(:user_in, :respect_in, :dollaz_in, :description_in, :homework_in, :file_name, :file_type, :file_size, :file_data, @id_out)");
            $insert_upload_stmt->bindParam(':user_in', $_SESSION['userid']);
            $insert_upload_stmt->bindParam(':respect_in', $post_respect);
            $insert_upload_stmt->bindParam(':dollaz_in', $post_dollaz);
            $insert_upload_stmt->bindParam(':description_in', $post_description);
            $insert_upload_stmt->bindParam(':homework_in', $post_homework);
            $insert_upload_stmt->bindParam(':file_name', $file_name);
            $insert_upload_stmt->bindParam(':file_type', $file_type);
            $insert_upload_stmt->bindParam(':file_size', $file_size);
            $insert_upload_stmt->bindParam(':file_data', $file_rawData);

            $insert_upload_stmt->execute();

            $insert_upload_stmt->closeCursor();
            $pdo->commit();
            $response = array('response' => SUCCESS);
          } else {
            $response = array('response' => SQL_FAIL, 'error' => 'no response');
            $pdo->rollBack();
          }
          //YAY hat geklappt
        } catch (Exception $e) {
          $pdo->rollBack();
          $response = array('response' => SQL_FAIL, 'error' => $e);
        }
      } else {
        $response = array('response' => FAIL, 'error' => 'File to big');
      }
    } elseif (isset($_FILES['file']['name']) && $_FILES['file']['name'] != "") {
      $response = array('response' => FAIL, 'error' => 'Probably to big');
    } else {
      $response = array('response' => FAIL, 'error' => $_FILES);
    }
  } else {
    $response = array('response' => NOT_LOGGED_IN);
  }
  echo json_encode($response);
?>
