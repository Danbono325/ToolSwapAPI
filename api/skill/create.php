<?php
    // SET HEADER
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");
    //header("Access-Control-Allow-Credentials: true");
    header("Access-Control-Allow-Headers: Access-Control-Allow-Headers, 
            Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With");

    include_once '../../config/Database.php';
    include_once '../../models/Skill.php';
    include_once '../../models/User.php';

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

    // Instantiate user and skill object
    $skill = new Skill($conn);
    $user = new User($conn);

     // CHECK GET ID PARAMETER OR NOT
     if(isset($_GET['user_id'])) {
         //IF HAS ID PARAMETER
         $user_id = filter_var($_GET['user_id'], FILTER_VALIDATE_INT,[
             'options' => [
                 'default' => 'user',
                 'min_range' => 1
             ]
         ]);
     } else {
         echo json_encode(array('message' => 'No User Found'));
     }

    // Get raw posted data
    $data = json_decode(file_get_contents("php://input"));

    // If jwt is not empty
    if($jwt){
    
        // If decode succeed, check if its the right user and delete
        try {
    
            // Decode jwt
            $decoded = JWT::decode($jwt, $key, array('HS256'));
    
            $skill->description = $data->description;

            
            if($decoded->data->id == $user_id && $user->read($user_id)->rowCount() <= 0) {
                echo json_encode(array('message' => 'No User Found with '.$user_id));
            // Check user and Add Skill
            } else if($decoded->data->id == $user_id && $skill->addSkill($user_id)){

                http_response_code(200);


                echo json_encode(
                    array('Message'=>'Skill Added')
                );
            } else {
                echo json_encode(
                    array('Message'=> 'Skill not Added')
                );
            }

        } // If decode fails, it means jwt is invalid
        catch (Exception $e){
         
            // Set response code
            http_response_code(401);
         
            // Show error message
            echo json_encode(array(
                "message" => "Access denied.",
                "error" => $e->getMessage()
            ));
        }
    } else {
        echo json_encode(array("Message" => "Not authorized no token found"));
    }
?>