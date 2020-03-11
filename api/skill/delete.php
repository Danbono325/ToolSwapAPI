<?php
    // SET HEADER
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: DELETE");
    //header("Access-Control-Allow-Credentials: true");
    header("Access-Control-Allow-Headers: Access-Control-Allow-Headers, 
            Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With");



    include_once '../../config/Database.php';
    include_once '../../models/Skill.php';

    $database = new Database();
    $conn = $database->dbConnection();

    //Instantiate listing object
    $skill = new Skill($conn);

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
    else{
        echo json_encode(array('message' => 'No User Found'));
    }


    // Delete Skill
    if($skill->removeSkill($userID)){
        echo json_encode(
            array('Message'=>'Skill Deleted')
        );
    } else {
        echo json_encode(
            array('Message'=> 'Skill not Deleted')
        );
    }
?>