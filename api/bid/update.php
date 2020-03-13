<?php
    // SET HEADER
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: PUT");
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

    // Get raw posted data
    $data = json_decode(file_get_contents("php://input"));

    $bid->amount = $data->amount;
    $bid->estimatedTimeDays = $data->estimatedTimeDays;
    $bid->estimatedTimeWeeks = $data->estimatedTimeWeeks;
    $bid->estimatedTimeMonths = $data->estimatedTimeMonths;
    $bid->estimatedTimeYears = $data->estimatedTimeYears;
    $bid->message = $data->message;


    // Update bid
    
    if($bid->readBid($bidID)->rowCount() <= 0 ){
        echo json_encode(array('message' => 'No Bid Found with '.$bidID));
    } else if($bid->update($bidID)){
        echo json_encode(
            array('Message'=>'Bid Updated')
        );
    } else {
        echo json_encode(
            array('Message'=> 'Bid not Updated')
        );
    }
?>