<?php
    // SET HEADER
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: access");
    header("Access-Control-Allow-Methods: GET");
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

    $user = new User($conn);

    $jwt = $_SERVER["HTTP_X_AUTH"];

    // CHECK GET ID PARAMETER OR NOT
    if(isset($_GET['user_id']))
    {
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


    if($jwt){
        // if decode succeed, show user details
        try {

            // decode jwt
            $decoded = JWT::decode($jwt, $key, array('HS256'));

            //User Query
            $result = $user->read($user_id);

            $num = $result->rowCount();

            $user_data['data'] = array();

            if($num > 0){
                while($row = $result->fetch(PDO::FETCH_ASSOC)){
                    extract($row);
            
                    $userData = array(
                        'user_id' => $user_id,
                        'username' => $username,
                        'email' => $email,
                        'firstname' => $firstname,
                        'lastname' => $lastname
                    );
                    array_push($user_data['data'], $userData);
                }
                echo json_encode($user_data);
            } else {
                //No User Found
                http_response_code(404);


                echo json_encode(array('message' => 'No User Found'));
            }
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
        echo json_encode(array("Message" => "Not authorized no token found"));
    }
?>