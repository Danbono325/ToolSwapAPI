<?php 
     // SET HEADER
     header("Access-Control-Allow-Origin: *");
     header("Content-Type: application/json; charset=UTF-8");
     header("Access-Control-Allow-Methods: POST");
     //header("Access-Control-Allow-Credentials: true");
     header("Access-Control-Allow-Headers: Access-Control-Allow-Headers, 
             Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With");

    include_once '../../config/Database.php';
    include_once '../../models/User.php';

    //Used for generating token
    include_once '../../config/core.php';
    include_once '../../libs/php-jwt-master/src/BeforeValidException.php';
    include_once '../../libs/php-jwt-master/src/ExpiredException.php';
    include_once '../../libs/php-jwt-master/src/SignatureInvalidException.php';
    include_once '../../libs/php-jwt-master/src/JWT.php';
    use \Firebase\JWT\JWT;
    

    $jwt = $_SERVER["HTTP_X_AUTH"];

    // if jwt is not empty
    if($jwt){
    
        // if decode succeed, show user details
        try {
    
            // decode jwt
            $decoded = JWT::decode($jwt, $key, array('HS256'));

            http_response_code(200);

    
            // show user properties
            echo json_encode(array(
                "message" => "Access granted.",
                "data" => $decoded->data
            ));

        } // if decode fails, it means jwt is invalid
        catch (Exception $e){
         
            // set response code
            http_response_code(401);
         
            // show error message
            echo json_encode(array(
                "message" => "Access denied.",
                "error" => $e->getMessage()
            ));
        }
    } else {
        http_response_code(401);

        echo json_encode(array("Message" => "Not authorized no token found"));
    }
?>

    

