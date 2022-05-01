<?php
//Page 7

require_once 'menu.php';
require_once 'mydbfuncs.php';
//This page to buy trip

if($_SESSION['userType'] != $user_type_client){      //if user type not client "ל"
    header("Location:LOGIN.php");
}

$userNumx = $_SESSION['userNum'];       //getting the current userNum 

if (isset($_GET['journeyNumber'])) {
    
    //$userNumx = $_SESSION['userNum'];    
    $tripNum = $_GET['journeyNumber'];

    /*
      check if journey value in URL also viewed in user "TripList" Page. otherwise back to "TripList" page
      This in order to prevent the user to order a journey outside the system rules by changing journey value in URL
      And as well, preventing displaying page with no values if journey number value doesn't exist in the database
     */

    
    //This query returns list of journeys that user already registered to
    $query_registered_trips_list = "Select O.orderJournyNum from tblorders O, tbljourneys J Where O.orderJournyNum = J.journeyNum"
            . " And orderUserNum = $userNumx";
    
    //This query returns list of UPCOMING journeys as well as the journeys which the user didn't register to (Same as TripList Page)
    $query_trips_num = "Select journeyNum From tbljourneys Where journeyStartDate>NOW() AND journeyNum NOT IN ($query_registered_trips_list)";


    $user_journeys_stm = $db->prepare($query_trips_num);
    $user_journeys_stm->execute();
    $user_journeys_array = $user_journeys_stm->fetchAll();       //now $user_journeys_array stores the requested data
    $user_journeys_stm->closeCursor();


    $user_journeys_array_fetched = array();
    foreach ($user_journeys_array as $journey) {
        array_push($user_journeys_array_fetched, $journey['journeyNum']);
    }

    if (!(in_array($tripNum, $user_journeys_array_fetched))) {    //if the value changed manually in url NOT IN (!) user's "TripList" Page
        header("Location:TripList.php");    //back to user's "TripList" Page
    } else {
        
        /*
         * This confirms that the journey number value in URL is also viewed in user's "TripList" Page
         * so the requested data of the journey can be displayed
         */

        $query_trip = "Select journeyName, journeyDescription, journeyStartDate,journeyDuration,journeyPrice, journeyKosher,journeyAudiancesCode"
                . "  From tbljourneys Where journeyNum = $tripNum";

        //getting data from DB
        $trip_stm = $db->prepare($query_trip);
        $trip_stm->execute();
        $trip_info_array = $trip_stm->fetchAll();    
        $trip_stm->closeCursor();
    }
    
    
} elseif (isset($_POST['registertrip'])) {

    $tripNum = $_POST['tripNum'];
    $todays_date = date("Y-m-d");
    $quantity = filter_input(INPUT_POST, "orderquantity");

    $query_trip = "Select journeyName, journeyDescription, journeyStartDate,journeyDuration,journeyPrice, journeyKosher,journeyAudiancesCode"
            . "  From tbljourneys Where journeyNum = $tripNum";


    //getting data from DB
    $trip_stm = $db->prepare($query_trip);
    $trip_stm->execute();
    $trip_info_array = $trip_stm->fetchAll();       //now $trips array stores the requested data
    $trip_stm->closeCursor();

    $trip_price = $trip_info_array[0]['journeyPrice'];

    if (empty($quantity) || $quantity < 0 || $quantity > 100) {
        $err_text = "Please choose your order quantity (Only allowed between 1 And 100)";
    } else {     //means no error in the input then insert into table of orders
        $query_order = "INSERT INTO tblorders (orderUserNum, orderJournyNum, orderQuantity, "
                . "orederDate) VALUES (:orderUserNum,:orderJournyNum, :orderQuantity,:orederDate)";

        $stm_order = $db->prepare($query_order);
        $stm_order->bindValue(':orderUserNum', $userNumx);
        $stm_order->bindValue(':orderJournyNum', $tripNum);
        $stm_order->bindValue(':orderQuantity', $quantity);
        $stm_order->bindValue(':orederDate', $todays_date);
        $execute_success = $stm_order->execute();
        $stm_order->closeCursor();

        if ($execute_success) {  //if trip added successfully, print link to trips page
            echo "<html><style>.quantityform, .tripDescText, #descLabel, #trip, #pageTitle{display:none;} </style></html>";   //hide trip info after registering
            echo('<br><br><br><br><p><div style="text-align: center;font-size:22px;">Trip Registered Successfully! Your order Price is: <b>'
            . $trip_price * $quantity . ' </b><br><a href="TripPage.php">Click Here to view all your orders</a></div></p>');
        } else {
            echo "ERROR";
        }
    }
} else {
    //This in order to prevent viewing a page with no values in case the user refresh before clicking on the link
    header("Location:TripPage.php");
}
?>




<html>
    <head>
        <meta charset='utf-8'>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="tableCSS.css">
        <title>Organized Trips - Mary 2020</title>
    </head>
    <body>

        <br><div id="pageTitle">
            <p>Register Trip Page</p>
        </div><br>

        <br><br>
        <table id="trip" >
            <tr>
                <th>Name</th>
                <th>Date</th>
                <th>Duration(Days)</th>
                <th>Price</th>
                <th>Kosher</th>
                <th>Audience</th>
                <th></th>
            </tr>
            <tr>
                <td><?php echo $trip_info_array[0]['journeyName']; ?></td>
                <td><?php echo $trip_info_array[0]['journeyStartDate']; ?></td>
                <td><?php echo $trip_info_array[0]['journeyDuration']; ?></td>
                <td><?php echo $trip_info_array[0]['journeyPrice']; ?></td>
                <td><?php echo func_kosher($trip_info_array[0]['journeyKosher']); ?></td>
                <td><?php echo $arrayTargetedAudience[$trip_info_array[0]['journeyAudiancesCode']]; ?></td>
            </tr>
        </table>
        <br>

        <div id="descLabel"><label for="tripDesc"><b>Journey Description</b></label></div>
        <textarea class="tripDescText" name="tripDesc" readonly><?php echo $trip_info_array[0]['journeyDescription'] ?></textarea>

        <br><br><br>
        <div class="quantityform">
            <form action="Registration.php" method="post">           
                <label id="quantitytext" for="orderquantity">Please choose your order quantity</label><br><br>
                <input type="number" name="orderquantity">
                <input type="submit" name="registertrip" value="Register">  
                <input type="hidden" name="tripNum" value="<?php echo $tripNum ?>" />
            </form>
        </div>

        <br><div style="text-align: center;"><h2 id="errormsg" style="color:red;">
                <?php require_once 'error.php' ?>
            </h2></div>

    </body>
</html>
