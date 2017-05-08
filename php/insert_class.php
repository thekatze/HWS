<?php
    session_start();
    include 'functions.php';
    include 'constants.php';

    //Creating a connection to the Database
    $pdo = create_pdo();

    //Controll if the user is still logged in
    if (isset($_SESSION['login']) && $_SESSION['login'] == 1) {
        try {
            $post_data = file_get_contents("php://input");
            $post_name = json_decode($post_data)->{'n'};

            $user = $_SESSION['userid'];


            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->beginTransaction();

            $insert_class_stmt = $pdo->prepare("call insert_class(:classname_in, :iduser_in, @id_out)");
            $insert_class_stmt->bindParam(':classname_in', $post_name);
            $insert_class_stmt->bindParam(':iduser_in', $user);

            $insert_class_stmt->execute();
            $pdo->query("select @id_out")->fetch()[0];
            $insert_class_stmt->closeCursor();
            $pdo->commit();
            $response = array('response' => SUCCESS);
        } catch (Exception $e) {
            //Response if there is a SQL Error
            $pdo->rollBack();
            $response = array('response' => SQL_FAIL);
        }
    } else {
    //Response if user is not logged in
    $response = array('response' => NOT_LOGGED_IN);
    }
    echo json_encode($response);
?>
