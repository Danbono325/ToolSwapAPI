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


    $database = new Database();
    $conn = $database->dbConnection();

    //Instantiate user object
    $skill = new Skill($conn);
    $user = new User($conn);

     // CHECK GET ID PARAMETER OR NOT
     if(isset($_GET['user_id'])){
         //IF HAS ID PARAMETER
         $userID = filter_var($_GET['user_id'], FILTER_VALIDATE_INT,[
             'options' => [
                 'default' => 'user',
                 'min_range' => 1
             ]
         ]);
     }
     else {
         echo json_encode(array('message' => 'No User Found'));
     }

    // Get raw posted data
    $data = json_decode(file_get_contents("php://input"));

    $skill->description = $data->description;

    if($user->read($userID)->rowCount() <= 0) {
        echo json_encode(array('message' => 'No User Found with '.$userID));
    } else if($skill->addSkill($userID)){
        echo json_encode(
            array('Message'=>'Skill Added')
        );
    } else {
        echo json_encode(
            array('Message'=> 'Skill not Added')
        );
    }
?>