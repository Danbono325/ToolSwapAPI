<?php
    // SET HEADER
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");
    //header("Access-Control-Allow-Credentials: true");
    header("Access-Control-Allow-Headers: Access-Control-Allow-Headers, 
            Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With");



    include_once '../../config/Database.php';
    include_once '../../models/Listing.php';
    include_once '../../models/User.php';


    $database = new Database();
    $conn = $database->dbConnection();

    //Instantiate listing object
    $listing = new Listing($conn);
    $user = new User($conn);


    if(isset($_GET['user_id'])){
        //IF HAS ID PARAMETER
        $user_id = filter_var($_GET['user_id'], FILTER_VALIDATE_INT,[
            'options' => [
                'default' => 'user',
                'min_range' => 1
            ]
        ]);

    }
    else {
        echo json_encode(array('message' => 'No User Found'));
    }

    $user->user_id = $user_id;

    // Get raw posted data
    $data = json_decode(file_get_contents("php://input"));

    $listing->title = $data->title;
    $listing->description = $data->description;
    $listing->expectedDays = $data->expectedDays;
    $listing->expectedWeeks = $data->expectedWeeks;
    $listing->expectedMonths = $data->expectedMonths;
    $listing->expectedYears = $data->expectedYears;


    if($user->read()->rowCount() <= 0 ){
        echo json_encode(array('message' => 'No User Found with '.$user_id));
    } else if($listing->create($user_id)){
        echo json_encode(
            array('Message'=>'Listing Created')
        );
    } else {
        echo json_encode(
            array('Message'=> 'Listing not Created')
        );
    }
?>

