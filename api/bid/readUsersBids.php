<?php
    // SET HEADER
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: access");
    header("Access-Control-Allow-Methods: GET");
    header("Access-Control-Allow-Credentials: true");
    header("Content-Type: application/json; charset=UTF-8");


    include_once '../../config/Database.php';
    include_once '../../models/Bid.php';

    $database = new Database();
    $conn = $database->dbConnection();

    $bid = new Bid($conn);

    // CHECK GET ID PARAMETER OR NOT
    if(isset($_GET['user_id']))
    {
        //IF HAS ID PARAMETER
        $userID = filter_var($_GET['user_id'], FILTER_VALIDATE_INT,[
            'options' => [
                'default' => 'user',
                'min_range' => 1
            ]
        ]);
    }
    else{
        echo json_encode(array('message' => 'No Bids Found for user '.$userID));
    }

    // User's bids Query
    $result = $bid->readUsersBids($userID);

    $num = $result->rowCount();

    $bidsData['data'] = array();

    if($num > 0){
        while($row = $result->fetch(PDO::FETCH_ASSOC)){
            extract($row);

            $bidItem = array(
                'bidID' => $idbids,
                'amount' => $amount,
                'estimatedTimeDays' => $estimatedTimeDays,
                'estimatedTimeWeeks' => $estimatedTimeWeeks,
                'estimatedTimeMonths' => $estimatedTimeMonths,
                'estimatedTimeYears' => $estimatedTimeYears,
                'message' => $message
            );
            array_push($bidsData['data'], $bidItem);
        }
        echo json_encode($bidsData);
    } else {
        //No Bids Found
        echo json_encode(array('message' => 'No Bid Found'));
    }
?>