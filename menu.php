<?php
require_once 'mydbfuncs.php';
?>

<html>
    <head>
        <meta charset='utf-8'>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="menu-style.css">
        <title>Organized Trips - Mary 2020</title>
    </head>
    <body>
        <?php if (isset($_SESSION['userNum'])) : ?>
        <div id='cssmenu'>
            <ul>
                <li class="left"><a style="color: whitesmoke;"><span>Welcome <strong><?php echo $_SESSION['userRealname']; ?></strong></span></a></li>
                <li class="left"><a href='EditPassword.php'><span>Change Password</span></a></li>
                <li class="left"><a href='DisengagementAction.php?logout="1"'><span>Log Out</span></a></li>

                <?php if($_SESSION['userType'] == "ל") : ?>
                <li><a href='TripList.php'><span>Trips List</span></a></li>
                <li><a href='TripPage.php'><span>My Trips</span></a></li>
                <?php endif; ?>  

                <?php if($_SESSION['userType'] == "מ") : ?>
                <li <a class="admin"><span>[Admin Page]</span></a></li>
                <li><a href='AddTrip.php'><span>Add New Trip</span></a></li>
                <li><a href='TripNotReleased.php'><span>Trips List</span></a></li>
                <?php endif; ?>  

            </ul>
        </div>
        <?php endif ?>

    </body>
</html>

