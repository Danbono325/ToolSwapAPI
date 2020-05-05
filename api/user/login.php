<?php
    // SET HEADER
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: access");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Allow-Credentials: true");
    header("Content-Type: application/json; charset=UTF-8");

    include_once '../../config/Database.php';
    include_once '../../models/User.php';

    //Used for generating token
    include_once '../../config/core.php';
    include_once '../../libs/php-jwt-master/src/BeforeValidException.php';
    include_once '../../libs/php-jwt-master/src/ExpiredException.php';
    include_once '../../libs/php-jwt-master/src/SignatureInvalidException.php';
    include_once '../../libs/php-jwt-master/src/JWT.php';
    use \Firebase\JWT\JWT;

    $database = new Database();
    $conn = $database->dbConnection();

    //Instantiate user object
    $user = new User($conn);

    // Get raw posted data
    $data = json_decode(file_get_contents("php://input"));

    $user->email = $data->email;
    $email_exists = $user->emailExists();

    // check if email exists and if password is correct
    if($email_exists && $data->password == $user->password){
        
        $token = array(
        "iss" => $iss,
        "aud" => $aud,
        "iat" => $iat,
        "nbf" => $nbf,
        "data" => array(
            "id" => $user->user_id,
            "firstname" => $user->firstname,
            "lastname" => $user->lastname,
            "email" => $user->email
        )
        );
    
        // set response code
        http_response_code(200);
    
        // generate jwt
        $jwt = JWT::encode($token, $key);
        echo json_encode(
                array(
                    "Message" => "Successful login.",
                    "jwt" => $jwt,
                    "user" => $user
                )
            );
    
    } else { // login failed
        // tell the user login failed
        echo json_encode(array("Message" => "Invalid Credentials."));
    }
?>