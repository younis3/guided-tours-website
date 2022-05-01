<?php
//page 9
require_once 'menu.php';
require_once 'mydbfuncs.php';

if($_SESSION['userType'] != $user_type_client){      //if user type not client "ל"
    header("Location:LOGIN.php");
}

$err_text = "";
$errors = array();


$userNumx = $_SESSION['userNum'];    //getting the current userNum (benefit from session data without query)

/* table view */

//This query returns list of journeys that user already registered to
$query_registered_user_trips_list = "Select O.orderJournyNum, O.orderQuantity, O.orderNum, J.journeyPrice, J.journeyName, J.journeyStartDate from"
        . " tblorders O, tbljourneys J Where O.orderUserNum = $userNumx AND O.orderJournyNum = J.journeyNum";


//getting data from DB
$user_trips_stm = $db->prepare($query_registered_user_trips_list);
$user_trips_stm->execute();
$user_trips = $user_trips_stm->fetchAll();       //now $trips array stores the requested data
$user_trips_stm->closeCursor();
/* * *** END Getting Data for the Table **** */




//direct to edit order page with order number
if (isset($_GET['editOrder'])) {
    $journeyOrderValue = $_GET['editOrder'];
    header("Location:EditOrder.php?orderNum=" . $journeyOrderValue);
    exit();
}



//delete order
if (isset($_GET['cancel'])) {

    $orderNumberValue = $_GET['cancel'];     //getting journeyNum
    echo $orderNumberValue;

    $query_del_order = "DELETE from tblorders Where orderNum = $orderNumberValue";
    try {   //to prevent fatal error
        $del_order_stm = $db->prepare($query_del_order);
        $del_order_stm->execute();
        $del_order_stm->closeCursor();
    } catch (Exception $e) {
        //getting the error message if it coudn't handle the query successfully
        //echo $e->getMessage();
        echo "Error!";
    }

    header("Location:TripPage.php");
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
            <p>List of my Orders<br><h6>* You can always cancel orders up to <b>10 days</b> before the trip *</h6></p>
    </div><br>



    <br><br>
    <table id="trip" >
        <tr>
            <th>Journey Number</th>
            <th>Journey Name</th>
            <th>Order Quantity</th>
            <th>Total Price</th>
            <th>Journey Date</th>
            <th></th>
        </tr>
        <?php foreach ($user_trips as $trip) : ?>
            <tr>
                <td><?php echo $trip['orderJournyNum']; ?></td>
                <td><?php echo $trip['journeyName']; ?></td>
                <td><?php echo $trip['orderQuantity']; ?></td>
                <td><?php echo $trip['orderQuantity'] * $trip['journeyPrice']; ?></td>
                <td><?php echo $trip['journeyStartDate']; ?></td>

                <td><?php
                    $now = strtotime(date("Y-m-d"));
                    $trip_date = strtotime($trip['journeyStartDate']);
                    $days_diff = (($trip_date - $now) / 60 / 60 / 24);
                    ?>  

                    <form action="TripPage.php" method="get">
                        <button type="submit" name="editOrder" value=<?php echo $trip['orderNum']; ?>>Edit Order</button>
                        <?php if ($days_diff > 10) : ?>
                            <button type="submit" name="cancel" value=<?php echo $trip['orderNum']; ?>>
                                <a onclick="return confirm('Are you sure to cancel this order?');">Cancel</a>
                            </button>
                        <?php endif; ?>
                    </form>

                </td>
            </tr>
        <?php endforeach; ?>
    </table>

</body>
</html>
