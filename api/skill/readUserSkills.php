<?php
    // SET HEADER
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: access");
    header("Access-Control-Allow-Methods: GET");
    header("Access-Control-Allow-Credentials: true");
    header("Content-Type: application/json; charset=UTF-8");


    include_once '../../config/Database.php';
    include_once '../../models/Skill.php';

    $database = new Database();
    $conn = $database->dbConnection();

    $skill = new Skill($conn);

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
    }
    else{
        echo json_encode(array('Message' => 'No user found'));
    }

    //Review Query
    $result = $skill->readUserSkills($user->user_id);

    $num = $result->rowCount();

    $skillsData['data'] = array();

    if($num > 0){
        while($row = $result->fetch(PDO::FETCH_ASSOC)){
            extract($row);

            $skillItem = array(
                'skillID' => $idskills,
                'description' => $description
            );
            array_push($skillsData['data'], $skillItem);
        }
        http_response_code(200);

        echo json_encode($skillsData);
    } else {
        //No Listings Found
        http_response_code(404);

        echo json_encode(array('Message' => 'No Skills Found'));
    }
?>