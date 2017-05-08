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
            $post_class = json_decode($post_data)->{'c'};

            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->beginTransaction();

            $update_acceptance_stmt = $pdo->prepare("call update_user_class_acceptance(:userid, :classid)");
            $update_acceptance_stmt->bindParam(':userid', $_SESSION['userid']);
            $update_acceptance_stmt->bindParam(':classid', $post_class);

            $update_acceptance_stmt->execute();

            $update_acceptance_stmt->closeCursor();

            $response = array(
            'response' => SUCCESS
            );

            $pdo->commit();
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
