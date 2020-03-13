<?php
    // SET HEADER
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: DELETE");
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
                'default' => 'review',
                'min_range' => 1
            ]
        ]);
    }
    else{
        echo json_encode(array('message' => 'No Review Found'));
    }


    // Delete Review
    if($review->readReview($reviewID)->rowCount() <= 0){
        echo json_encode(array('message' => 'No Review Found with '.$reviewID));
    } else if($review->delete($reviewID)){
        echo json_encode(
            array('Message'=>'Review Deleted')
        );
    } else {
        echo json_encode(
            array('Message'=> 'Review not Deleted')
        );
    }
?>