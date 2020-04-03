<?php
    // SET HEADER
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: access");
    header("Access-Control-Allow-Methods: GET");
    header("Access-Control-Allow-Credentials: true");
    header("Content-Type: application/json; charset=UTF-8");


    include_once '../../config/Database.php';
    include_once '../../models/Bid.php';

    //Used for generating token
    include_once '../../config/core.php';
    include_once '../../libs/php-jwt-master/src/BeforeValidException.php';
    include_once '../../libs/php-jwt-master/src/ExpiredException.php';
    include_once '../../libs/php-jwt-master/src/SignatureInvalidException.php';
    include_once '../../libs/php-jwt-master/src/JWT.php';
    use \Firebase\JWT\JWT;

    $jwt = $_SERVER["HTTP_X_AUTH"];

    $database = new Database();
    $conn = $database->dbConnection();

    // Instantiate bid object
    $bid = new Bid($conn);

    // CHECK GET ID PARAMETER OR NOT
    if(isset($_GET['user_id'])){
        //IF HAS ID PARAMETER
        $user_id = filter_var($_GET['user_id'], FILTER_VALIDATE_INT,[
            'options' => [
                'default' => 'user',
                'min_range' => 1
            ]
        ]);

        if(isset($_GET['bid_id'])){
        //IF HAS ID PARAMETER
        $bid->bid_id = filter_var($_GET['bid_id'], FILTER_VALIDATE_INT,[
            'options' => [
                'default' => 'listing',
                'min_range' => 1
            ]
        ]);
    }
    }
    else {
        echo json_encode(array('message' => 'No Bid or User Found'));
    }

    //$bid->bid_id = $bid_id;

    if($jwt) {

        // If decode succeed, check if its the right user and delete
        try {
            // Decode jwt
            $decoded = JWT::decode($jwt, $key, array('HS256'));

            //Checks if the bid belongs to this user
            if ($bid->userBidConfirm($user_id)->rowCount() <= 0 ){

                echo json_encode(array('Message'=>'No bid found for user and bid id'));

            // Checks with JWT token and update the bid
            } else if ($decoded->data->id == $user_id){

                // Bid Query
                $result = $bid->readBid($bidID);

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
                    http_response_code(200);

                    echo json_encode($bidsData);
                } else {
                    http_response_code(404);

                    //No Bid Found
                    echo json_encode(array('Message' => 'No Bids Found'));
                }
                
            } else {
                http_response_code(401);

                echo json_encode(
                    array('Message'=> 'Not your listing\'s bids')
                );
            }

        } // If decode fails, it means jwt is invalid
        catch (Exception $e){
        
            // Set response code
            http_response_code(401);
        
            // Show error message
            echo json_encode(array(
                "Message" => "Access denied.",
                "error" => $e->getMessage()
            ));
        }
    } else {
        http_response_code(401);

        echo json_encode(array("Message" => "Not authorized no token found"));
    }
?>