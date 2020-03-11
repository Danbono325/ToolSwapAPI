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

$database = new Database();
$conn = $database->dbConnection();

//Instantiate user object
$listing = new Listing($conn);

// Get raw posted data
$data = json_decode(file_get_contents("php://input"));

$listing->userID = $data->userID;
$listing->title = $data->title;
$listing->description = $data->description;
$listing->expectedDays = $data->expectedDays;
$listing->expectedWeeks = $data->expectedWeeks;
$listing->expectedMonths = $data->expectedMonths;
$listing->expectedYears = $data->expectedYears;


if($listing->create()){
    echo json_encode(
        array('Message'=>'Listing Created')
    );
} else {
    echo json_encode(
        array('Message'=> 'Listing not Created')
    );
}

?>

