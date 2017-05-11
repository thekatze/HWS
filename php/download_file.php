<?php
    session_start();

    include 'functions.php';
    include 'constants.php';

    $pdo = create_pdo();

    if (isset($_SESSION) && $_SESSION['login'] == 1) {
        try {
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->beginTransaction();

            if(isset($_GET['id'])) {
                $f_id = $_GET['id'];

                $get_file_stmt = $pdo->prepare("call download_file(:fid)");
                $get_file_stmt->bindParam(':fid', $f_id);

                $get_file_stmt->execute();
                $file = $get_file_stmt->fetch();

                $get_file_stmt->closeCursor();

                $f_name = $file['name'];
                $f_type = $file['type'];
                $f_size = $file['size'];
                $f_content = $file['data'];

                $get_file_stmt->closeCursor();

                header("Content-Type: $f_type");
                header("Content-Disposition: attachment; filename=$f_name");
                header("Content-length: $f_size");
                echo $f_content;
            }
            $pdo->commit();
            //YAY hat geklappt
        } catch (Exception $e) {
            $pdo->rollBack();
        }
    }
?>
