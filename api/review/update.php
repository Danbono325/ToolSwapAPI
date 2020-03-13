<?php
    // SET HEADER
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: PUT");
    //header("Access-Control-Allow-Credentials: true");
    header("Access-Control-Allow-Headers: Access-Control-Allow-Headers, 
            Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With");



    include_once '../../config/Database.php';
    include_once '../../models/Review.php';

    $database = new Database();
    $conn = $database->dbConnection();

    //Instantiate review object
    $review = new Review($conn);


    // CHECK GET ID PARAMETER OR NOT
    if(isset($_GET['review_id']))
    {
        //IF HAS ID PARAMETER
        $reviewID = filter_var($_GET['review_id'], FILTER_VALIDATE_INT,[
            'options' => [
                'default' => 'listing',
                'min_range' => 1
            ]
        ]);
    }
    else{
        echo json_encode(array('message' => 'No Review foun with id '.$reviewID));
    }

    // Get raw posted data
    $data = json_decode(file_get_contents("php://input"));

    $review->expectationScore = $data->expectationScore;
    $review->timeframeScore = $data->timeframeScore;
    $review->budgetScore = $data->budgetScore;
    $review->description = $data->description;

    // Update review
    if($review->readReview($reviewID)->rowCount() <= 0){
        echo json_encode(array('message' => 'No Review Found with '.$reviewID));
    } else if($review->update($reviewID)){
        echo json_encode(
            array('Message'=>'Review Updated')
        );
    } else {
        echo json_encode(
            array('Message'=> 'Review not Updated')
        );
    }
?>