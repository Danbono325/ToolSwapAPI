<?php
    // SET HEADER
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: DELETE");
    //header("Access-Control-Allow-Credentials: true");
    header("Access-Control-Allow-Headers: Access-Control-Allow-Headers, 
            Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With");



    include_once '../../config/Database.php';
    include_once '../../models/Listing.php';

    $database = new Database();
    $conn = $database->dbConnection();

    //Instantiate listing object
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
        echo json_encode(array('message' => 'No Listing Found'));
    }

    // Delete Listing
    if ($listing->readListing($listingID)->rowCount() <= 0 ){
        echo json_encode(array('Message'=>'No Listing found with '.$listingID));
    } else if($listing->delete($listingID)){
        echo json_encode(
            array('Message'=>'Listing Deleted')
        );
    } else {
        echo json_encode(
            array('Message'=> 'Listing not Deleted')
        );
    }
?>