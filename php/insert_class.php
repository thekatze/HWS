<?php
    //Setting up
    include 'functions.php';
    include 'constants.php';
    session_start();
    $pdo = create_pdo();

    //Ceck if user is logged in
    if (isset($_SESSION['login']) && $_SESSION['login'] == 1) {
        try {
            //Get the data out of the Post
            $post_data = file_get_contents("php://input");
            $post_name = json_decode($post_data)->{'n'};
            $user = $_SESSION['userid'];
            //Initialize the PDO Connection
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->beginTransaction();
            //Prepeare the Statement
            $insert_class_stmt = $pdo->prepare("call insert_class(:classname_in, :iduser_in, @id_out)");
            $insert_class_stmt->bindParam(':classname_in', $post_name);
            $insert_class_stmt->bindParam(':iduser_in', $user);

            $insert_class_stmt->execute();
            $pdo->query("select @id_out")->fetch()[0];
            $insert_class_stmt->closeCursor();
            $pdo->commit();
            //If everything went right responde with a Success
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
