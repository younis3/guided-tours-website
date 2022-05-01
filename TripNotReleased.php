<?php
//page 11
//for Admin
require_once 'menu.php';
require_once 'mydbfuncs.php';

if($_SESSION['userType'] != $user_type_manager){      //if user type not manager "מ"
    header("Location:LOGIN.php");
}

$err_text = "";
$errors = array();

$userNumx = $_SESSION['userNum'];    //getting the current userNum (benefit from session data without query)

/* table view */


$query_ordered_Journeys_list = "Select orderJournyNum from tblorders";

$query_trips_list = "Select journeyNum, journeyName, journeyStartDate,journeyDuration,journeyPrice, journeyKosher,journeyAudiancesCode"
        . "  From tbljourneys Where journeyStartDate>NOW() AND journeyNum NOT IN ($query_ordered_Journeys_list)";




//getting data from DB
$trips_stm = $db->prepare($query_trips_list);
$trips_stm->execute();
$trips_array = $trips_stm->fetchAll();       //now $trips array stores the requested data
$trips_stm->closeCursor();

/* * *** END Getting Data for the Table **** */




//direct to edit trip page with jouney number
if (isset($_GET['editTrip'])) {
    $journeyNumberValue = $_GET['editTrip'];
    header("Location:EditTripPage.php?journeyNumber=" . $journeyNumberValue);
    exit();
}



//delete journey
if (isset($_GET['deleteTrip'])) {

    $journeyNumberValue = $_GET['deleteTrip'];     //getting journeyNum


    $query_del_trip = "DELETE from tbljourneys Where journeyNum = $journeyNumberValue And"
            . " journeyNum NOT IN ($query_ordered_Journeys_list)";


    try {   //to prevent fatal error
        $trips_stm = $db->prepare($query_del_trip);
        $trips_stm->execute();
        $trips_stm->closeCursor();
    } catch (Exception $e) {
        //getting the error message if it coudn't handle the query successfully
        //echo $e->getMessage();
        echo "Error: Cannot remove a trip if already ordered by users!";
    }

    header("Location:TripNotReleased.php");
    exit();
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
            <p>List of Upcoming Trips Page<br><h6>*Already ordered Trips are hidden</h6></p>
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
        <?php foreach ($trips_array as $trip) : ?>
            <tr>
                <td><?php echo $trip['journeyName']; ?></td>
                <td><?php echo $trip['journeyStartDate']; ?></td>
                <td><?php echo $trip['journeyDuration']; ?></td>
                <td><?php echo $trip['journeyPrice']; ?></td>
                <td><?php echo func_kosher($trip['journeyKosher']); ?></td>
                <td><?php echo $arrayTargetedAudience[$trip['journeyAudiancesCode']]; ?></td>
                <td>       
                    <form action="TripNotReleased.php" method="get">
                        <button type="submit" name="editTrip" value=<?php echo $trip['journeyNum']; ?>>Edit</button>
                        <button type="submit" name="deleteTrip" value=<?php echo $trip['journeyNum']; ?>>
                            <a onclick="return confirm('Are you sure to delete the Journey?');">Delete</a>
                        </button>                                
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

</body>
</html>



