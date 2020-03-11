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

$database = new Database();
$conn = $database->dbConnection();

//Instantiate user object
$user = new User($conn);

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
    echo json_encode(array('message' => 'No User Found'));
}

// Get raw posted data
//$data = json_decode(file_get_contents("php://input"));

// Set ID to update
//$user->user_id = $data->user_id;


// Update user
if($user->delete($user_id)){
    echo json_encode(
        array('Message'=>'User Deleted')
    );
} else {
    echo json_encode(
        array('Message'=> 'User not Deleted')
    );
}

?>

