<?php
    // SET HEADER
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: DELETE");
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

    $jwt = $_SERVER["HTTP_X_AUTH"];

    // CHECK GET ID PARAMETER OR NOT
    if(isset($_GET['user_id']))
    {
        //IF HAS ID PARAMETER
        $user->user_id = filter_var($_GET['user_id'], FILTER_VALIDATE_INT,[
            'options' => [
                'default' => 'user',
                'min_range' => 1
            ]
        ]);
    } else {
        http_response_code(404);
        echo json_encode(array('message' => 'No User Found'));
    }

    // if jwt is not empty
    if($jwt) {
        // if decode succeed, show user details
        try {
            // decode jwt
            $decoded = JWT::decode($jwt, $key, array('HS256'));

            // Update user
            if($user->read()->rowCount() <= 0) {

                echo json_encode(array('Message' => 'No User Found with '.$user_id));

            } else if ($decoded->data->id == $user_id && $user->delete($user_id)){
                http_response_code(200);

                echo json_encode(
                    array('Message'=>'User Deleted')
                );
            } else {
                http_response_code(404);

                echo json_encode(
                    array('Message'=> 'User not Deleted')
                );
            }
            // if decode fails, it means jwt is invalid
        } catch (Exception $e) {
            
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

