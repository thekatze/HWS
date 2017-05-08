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
            $post_date = json_decode($post_data)->{'d'};
            $post_homework = json_decode($post_data)->{'h'};

            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->beginTransaction();

            $insert_homework_stmt = $pdo->prepare("call insert_homework(:idclass_in, :date_in, :name_in, @id_out)");
            $insert_homework_stmt->bindParam(':idclass_in', $post_class);
            $insert_homework_stmt->bindParam(':date_in', $date_in);
            $insert_homework_stmt->bindParam(':name_in', $post_homework);

            $date_in = $post_date->format('Y-m-d H:i:s');
            $insert_homework_stmt->execute();
            $pdo->query("select @id_out")->fetch()[0]);
            $insert_homework_stmt->closeCursor();
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
