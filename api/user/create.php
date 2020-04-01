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

    $database = new Database();
    $conn = $database->dbConnection();

    //Instantiate user object
    $user = new User($conn);

    // Get raw posted data
    $data = json_decode(file_get_contents("php://input"));

    $user->email = $data->email;
    $email_exists = $user->emailExists();

    $user->username = $data->username;
    $user_exists = $user->usernameExists();

    $user->password = $data->password;
    $user->email = $data->email;
    $user->firstname = $data->firstname;
    $user->lastname = $data->lastname;

    if(!empty($user->firstname) &&
        !empty($user->lastname) &&
        !empty($user->username) &&
        !empty($user->email) &&
        !empty($user->password)){
            if (!$email_exists) {
                if(!$user_exists){
                    if($user->create()){
                        
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
                                        "Message" => "User Created",
                                        "jwt" => $jwt
                                    )
                                );
                    } else {
                
                        http_response_code(400);
                
                        echo json_encode(
                            array('Message'=> 'User not Created')
                        );
                    }
                } else {
                    echo json_encode(array('Message'=> 'This username is already taken.'));
                }
        } else {
            echo json_encode(array('Message'=> 'There is already an acount with this email.'));
        }
    } else {
        echo json_encode(array('Message'=> 'Make sure to add all the required fields'));
    }
?>

