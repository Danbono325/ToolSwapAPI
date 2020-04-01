<?php
    // SET HEADER
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: access");
    header("Access-Control-Allow-Methods: GET");
    header("Access-Control-Allow-Credentials: true");
    header("Content-Type: application/json; charset=UTF-8");


    include_once '../../config/Database.php';
    include_once '../../models/Review.php';

    $database = new Database();
    $conn = $database->dbConnection();

    // Instantiate review object
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

    //Review Query
    $result = $review->readReview($reviewID);

    $num = $result->rowCount();

    $reviewsData['data'] = array();

    if($num > 0){
        while($row = $result->fetch(PDO::FETCH_ASSOC)){
            extract($row);

            $reviewItem = array(
                'expectationScore' => $expectationScore,
                'timeframeScore' => $timeframeScore,
                'budgetScore' => $budgetScore,
                'description' => $description
            );
            array_push($reviewsData['data'], $reviewItem);
        }
        echo json_encode($reviewsData);
    } else {
        //No Review Found
        echo json_encode(array('message' => 'No Review Found'));
    }
?>