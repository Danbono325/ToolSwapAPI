<?php
    // SET HEADER
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: DELETE");
    //header("Access-Control-Allow-Credentials: true");
    header("Access-Control-Allow-Headers: Access-Control-Allow-Headers, 
            Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With");



    include_once '../../config/Database.php';
    include_once '../../models/Bid.php';

    $database = new Database();
    $conn = $database->dbConnection();

    //Instantiate bid object
    $bid = new Bid($conn);

    // CHECK GET ID PARAMETER OR NOT
    if(isset($_GET['bid_id']))
    {
        //IF HAS ID PARAMETER
        $bidID = filter_var($_GET['bid_id'], FILTER_VALIDATE_INT,[
            'options' => [
                'default' => 'bid',
                'min_range' => 1
            ]
        ]);
    }
    else{
        echo json_encode(array('message' => 'No Bid Found'));
    }


    // Delete Bid
    if($bid->readBid($bidID)->rowCount() <= 0 ){
        echo json_encode(array('message' => 'No Bid Found with '.$bidID));
    } else if($bid->delete($bidID)){
        echo json_encode(
            array('Message'=>'Bid Deleted')
        );
    } else {
        echo json_encode(
            array('Message'=> 'Bid not Deleted')
        );
    }
?>