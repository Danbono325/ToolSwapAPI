<?php
    // SET HEADER
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: access");
    header("Access-Control-Allow-Methods: GET");
    header("Access-Control-Allow-Credentials: true");
    header("Content-Type: application/json; charset=UTF-8");


    include_once '../../config/Database.php';
    include_once '../../models/Listing.php';

    $database = new Database();
    $conn = $database->dbConnection();

    $listing = new Listing($conn);

    //Uncompleted Listings Query
    $result = $listing->readAllUncompleted();

    $num = $result->rowCount();

    $listingsData['data'] = array();

    if($num > 0){
        while($row = $result->fetch(PDO::FETCH_ASSOC)){
            extract($row);

            $listingItem = array(
                'listingID' => $idlistings,
                'title' => $title,
                'description' => $description,
                'expectedDays' => $expectedDays,
                'expectedWeeks' => $expectedWeeks,
                'expectedMonths' => $expectedMonths,
                'expectedYears' => $expectedYears,
                'completed' => $completed,
                'userID' => $users_idusers
            );
            array_push($listingsData['data'], $listingItem);
        }
        http_response_code(200);

        echo json_encode($listingsData);
    } else {
        //No Listings Found
        http_response_code(200);

        echo json_encode($listingsData);
    }
?>