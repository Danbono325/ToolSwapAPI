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

    //Instantiate bid object
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
        } else {
            http_response_code(404);
            echo json_encode(array('Message' => 'No Review or User Found'));
        }
    } else {
        http_response_code(404);
        
        echo json_encode(array('Message' => 'No Review or User Found'));
    }

    // $bid->bid_id = $bid_id;

    if($jwt) {

        // If decode succeed, check if its the right user and delete
        try {
            // Decode jwt
            $decoded = JWT::decode($jwt, $key, array('HS256'));

            // Get raw posted data
            $data = json_decode(file_get_contents("php://input"));

            $bid->amount = $data->amount;
            $bid->estimatedTimeDays = $data->estimatedTimeDays;
            $bid->estimatedTimeWeeks = $data->estimatedTimeWeeks;
            $bid->estimatedTimeMonths = $data->estimatedTimeMonths;
            $bid->estimatedTimeYears = $data->estimatedTimeYears;
            $bid->message = $data->message;

            //Checks if the bid belongs to this user
            if ($bid->userBidConfirm($user_id)->rowCount() <= 0 ){

                echo json_encode(array('Message'=>'No Bid Listing found with '.$bid->bid_id));

            // Checks with JWT token and update the bid
            } else if ($decoded->data->id == $user_id && $bid->update()){
                http_response_code(200);
                
                echo json_encode(
                    array('Message'=>'Bid Updated')
                );
            } else {

                echo json_encode(
                    array('Message'=> 'Bid not Updated')
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