<?php
    //Setting up
    include 'functions.php';
    include 'constants.php';
    session_start();
    $pdo = create_pdo();

    //Check if the User is logged in
    if (isset($_SESSION) && $_SESSION['login'] == 1) {
        try {
            //Initialize the PDO Connection
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->beginTransaction();
            //Check if there really is a request
            if(isset($_GET['id'])) {
                $f_id = $_GET['id'];
                //Prepare the Statement
                $get_file_stmt = $pdo->prepare("call download_file(:fid)");
                $get_file_stmt->bindParam(':fid', $f_id);

                $get_file_stmt->execute();
                $file = $get_file_stmt->fetch();

                $get_file_stmt->closeCursor();

                //Write all the Data down
                $f_name = $file['name'];
                $f_type = $file['type'];
                $f_size = $file['size'];
                $f_content = $file['data'];

                $get_file_stmt->closeCursor();

                //Output as download
                header("Content-Type: $f_type");
                header("Content-Disposition: attachment; filename=$f_name");
                header("Content-length: $f_size");
                echo $f_content;
            }
            $pdo->commit();
            //YAY hat geklappt
        } catch (Exception $e) {
            //If there went something wrong -> Rollback
            $pdo->rollBack();
        }
    }
?>
