<?php
  session_start();

  include 'functions.php';

  $pdo = create_pdo();

  if (isset($_SESSION) && $_SESSION['login'] == 1) {
    try {
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $pdo->beginTransaction();

      if(isset($_GET['id'])) {
        $f_id = $_GET['id'];

        $get_file_stmt = $pdo->prepare("call get_file_id(:fid)");
        $get_file_stmt->bindParam(':fid', $f_id);

        $get_file_stmt->execute();
        $file = $get_file_stmt->fetch();

        $get_file_stmt->closeCursor();

        $f_name = $file['name'];
        $f_type = $file['type'];
        $f_size = $file['size'];
        $f_content = $file['data'];

        $get_file_stmt->closeCursor();

        header("Content-length: $f_size");
        header("Content-type: $f_type");
        header("Content-Disposition: attachment; filename=$f_name");
        echo $f_content;
      } else {
        echo "no File selected";
      }

      $pdo->commit();
      //YAY hat geklappt
    } catch (Exception $e) {
      $pdo->rollBack();
      echo "SQL Error";
    }
  } else {
    echo 'Not logged in';
  }

?>
