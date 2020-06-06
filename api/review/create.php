<?php
    // SET HEADER
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");
    //header("Access-Control-Allow-Credentials: true");
    header("Access-Control-Allow-Headers: Access-Control-Allow-Headers, 
            Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With");



    include_once '../../config/Database.php';
    include_once '../../models/Review.php';
    include_once '../../models/Listing.php';

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

    //Instantiate review object
    $review = new Review($conn);
    $listing = new Listing($conn);


    if(isset($_GET['user_id'])){
        //IF HAS ID PARAMETER
        $user_id = filter_var($_GET['user_id'], FILTER_VALIDATE_INT,[
            'options' => [
                'default' => 'user',
                'min_range' => 1
            ]
        ]);

        if(isset($_GET['listing_id'])){
           //IF HAS ID PARAMETER
           $listing->idlistings = filter_var($_GET['listing_id'], FILTER_VALIDATE_INT,[
               'options' => [
                   'default' => 'listing',
                   'min_range' => 1
               ]
           ]);
       } else {
        http_response_code(404);
        echo json_encode(array('Message' => 'No Listing Found'));
    }
    }
    else {
        http_response_code(404);
        echo json_encode(array('Message' => 'No User Found'));
    }

     if($jwt) {
    
        // If decode succeed, check if its the right user and delete
        try {
    
            // Decode jwt
            $decoded = JWT::decode($jwt, $key, array('HS256'));

            // Get raw posted data
            $data = json_decode(file_get_contents("php://input"));

            $review->expectationScore = $data->expectationScore;
            $review->timeframeScore = $data->timeframeScore;
            $review->budgetScore = $data->budgetScore;
            $review->description = $data->description;

            // Create Review
            if ($listing->userListingConfirm($user_id)->rowCount() <= 0 ){
                echo json_encode(array('Message'=>'No Listing found with '.$listing_id));
            } else if ($decoded->data->id == $user_id && $review->create($user_id, $listing_id)){
                http_response_code(200);
                
                echo json_encode(
                    array('Message'=>'Review Created')
                );
            } else {
                echo json_encode(
                    array('Message'=> 'Review not Created')
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