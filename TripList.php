<?php
//page 6
require_once 'menu.php';
require_once 'mydbfuncs.php';

if($_SESSION['userType'] != $user_type_client){      //if user type not client "ל"
    header("Location:LOGIN.php");
}


$err_text = "";
$errors = array();

$userNumx = $_SESSION['userNum'];    //getting the current userNum (benefit from session data without query)

/*table view*/

//This query returns list of journeys that user already registered to
$query_registered_trips_list = "(Select O.orderJournyNum from tblorders O, tbljourneys J Where O.orderJournyNum = J.journeyNum"
        . " And orderUserNum = $userNumx)";    

if (isset($_POST['searchTrip'])) {      //query search (Like) on journey description
    $search_text = filter_input(INPUT_POST, "search");
    $query_trips = "Select journeyNum, journeyName, journeyStartDate,journeyDuration,journeyPrice, journeyKosher,journeyAudiancesCode"
            . "  From tbljourneys Where journeyStartDate>NOW() AND journeyNum NOT IN $query_registered_trips_list And"
            . " journeyDescription LIKE '%$search_text%'";
    
} else {    //view all trips  
    $query_trips = "Select journeyNum, journeyName, journeyStartDate,journeyDuration,journeyPrice, journeyKosher,journeyAudiancesCode"
            . "  From tbljourneys Where journeyStartDate>NOW() AND journeyNum NOT IN $query_registered_trips_list";
}


//getting data from DB
$trips_stm = $db->prepare($query_trips);
$trips_stm->execute();
$trips = $trips_stm->fetchAll();       //now $trips array stores the requested data
$trips_stm->closeCursor();

/***** END Getting Data for the Table *****/




//direct to buy trip page with jouney number
if (isset($_GET['buyTrip'])) {   
    $journeyNumberValue = $_GET['buyTrip'];
    header("Location:Registration.php?journeyNumber=".$journeyNumberValue);
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

        <div class="searchform">
            <form action="TripList.php" method="post">
                <input type="text" name="search" placeholder="Search">
                <label for="search"></label>
                <input type="submit" name="searchTrip" value="Search">  
                <input type="submit" name="allTrips" value="All Trips"> 
            </form>
        </div>

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
            <?php foreach ($trips as $trip) : ?>
                <tr>
                    <td><?php echo $trip['journeyName']; ?></td>
                    <td><?php echo $trip['journeyStartDate']; ?></td>
                    <td><?php echo $trip['journeyDuration']; ?></td>
                    <td><?php echo $trip['journeyPrice']; ?></td>
                    <td><?php echo func_kosher($trip['journeyKosher']); ?></td>
                    <td><?php echo $arrayTargetedAudience[$trip['journeyAudiancesCode']]; ?></td>
                    <td>       
                        <form action="TripList.php" method="get">
                            <button type="submit" name="buyTrip" value=<?php echo $trip['journeyNum']; ?>>Register Trip</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>

    </body>
</html>






