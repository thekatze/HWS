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
            $post_upload = json_decode($post_data)->{'u'};


            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->beginTransaction();


            $get_upload_stmt = $pdo->prepare("call 	get_buy_info(:uploadid, :userid)");
            $get_upload_stmt->bindParam(':uploadid', $post_upload);
            $get_upload_stmt->bindParam(':userid', $_SESSION['userid']);

            $get_upload_stmt->execute();
            $upload = $get_upload_stmt->fetch();
            $get_upload_stmt->closeCursor();

            $buyerRes = $upload['respect_user'];
            $sellerRes = $upload['respect_seller'];
            $buyerDollaz = $upload['dollaz_user'];
            $costDollaz = $upload['dollaz_cost'];
            $costRespect = $upload['respect_cost'];

            if ($buyerRes >= $costRespect && $buyerDollaz >= $costDollaz) {

                //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~//
                //  All credit for this formula  //
                //           goes to             //
                //          TheKatze             //
                //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~//
                $respect = ceil(abs(log($buyerRes/(abs($sellerRes)+1), 10)) * pow($buyerRes, 1/1.7) + pow($costDollaz/10, -1/2));



                $insert_buy_stmt = $pdo->prepare("call 	insert_upload_bought(:userid, :uploadid, :respect)");
                $insert_buy_stmt->bindParam(':userid', $_SESSION['userid']);
                $insert_buy_stmt->bindParam(':uploadid', $post_upload);
                $insert_buy_stmt->bindParam(':respect', $respect);

                $insert_buy_stmt->execute();

                $insert_buy_stmt->closeCursor();


                $response = array('response' => SUCCESS);
                $pdo->commit();
            } else {
                $response = array('response' => FAIL, 'error' => "Not Enought Money or Respect");
                $pdo->rollBack();
            }
        } catch (Exception $e) {
            //Response if there is a SQL Error
            $response = array('response' => SQL_FAIL, 'error' => $e);
            $pdo->rollBack();
        }
    } else {
        //Response if user is not logged in
        $response = array('response' => NOT_LOGGED_IN);
    }
    echo json_encode($response);
?>
