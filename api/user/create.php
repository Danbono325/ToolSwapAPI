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

$database = new Database();
$conn = $database->dbConnection();

//Instantiate user object
$user = new User($conn);

// Get raw posted data
$data = json_decode(file_get_contents("php://input"));

$user->username = $data->username;
$user->password = $data->password;
$user->email = $data->email;
$user->firstname = $data->firstname;
$user->lastname = $data->lastname;

if($user->create()){
    echo json_encode(
        array('Message'=>'User Created')
    );
} else {
    echo json_encode(
        array('Message'=> 'User not Created')
    );
}

?>

