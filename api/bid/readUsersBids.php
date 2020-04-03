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

    $bid = new Bid($conn);

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
    }
    else{
        echo json_encode(array('Message' => 'No User id found'));
    }

    if($jwt) {

        // If decode succeed, check if its the right user and delete
        try {
            // Decode jwt
            $decoded = JWT::decode($jwt, $key, array('HS256'));
            
             if ($decoded->data->id == $user_id){

                    // User's bids Query
                    $result = $bid->readUsersBids($user_id);

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
                        //No Bids Found
                        http_response_code(404);
                        echo json_encode(array('Message' => 'No Bid Found'));
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