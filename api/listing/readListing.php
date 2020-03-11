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

    // CHECK GET ID PARAMETER OR NOT
    if(isset($_GET['listing_id']))
    {
        //IF HAS ID PARAMETER
        $listingID = filter_var($_GET['listing_id'], FILTER_VALIDATE_INT,[
            'options' => [
                'default' => 'listing',
                'min_range' => 1
            ]
        ]);
    }
    else{
        echo json_encode(array('message' => 'No Listing Found for listing'.listing_id));
    }

    //User Query
    $result = $listing->readListing($listingID);

    $num = $result->rowCount();

    //$userListings = array();
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
                'completed' => $completed
            );
            array_push($listingsData['data'], $listingItem);
        }
        echo json_encode($listingsData);
    } else {
        //No Listing Found
        echo json_encode(array('message' => 'No Listings Found'));
    }
?>