<?php
// SET HEADER
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header("Content-Type: application/json; charset=UTF-8");


include_once '../../config/Database.php';
include_once '../../models/User.php';

$database = new Database();
$conn = $database->dbConnection();

$user = new User($conn);

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
    echo json_encode(array('Message' => 'No User Found'));
}

//User Query
$result = $user->read();

$num = $result->rowCount();

$user_data['data'] = array();

if($num > 0){
    while($row = $result->fetch(PDO::FETCH_ASSOC)){
        extract($row);

        $userData = array(
            'user_id' => $idusers,
            'username' => $username,
            'email' => $email,
            'firstname' => $firstname,
            'lastname' => $lastname
        );
        array_push($user_data['data'], $userData);
    }
    http_response_code(200);

    echo json_encode($user_data);
} else {
    //No User Found
    http_response_code(404);
    echo json_encode(array('Message' => 'No User Found'));
}
?>