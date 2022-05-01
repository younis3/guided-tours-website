<?php
require_once 'mydbfuncs.php';

$err_text = "";
$errors = array();


if (isset($_POST['login'])) {

    $email = filter_input(INPUT_POST, "user_email");
    $password = filter_input(INPUT_POST, "user_password");

    if (empty($email)) {
        array_push($errors, "Email is required");
    } elseif (empty($password)) {
        array_push($errors, "Password is required");
    } else {
        $user_login_info_query = "SELECT * FROM tblusers WHERE userEmail='$email' LIMIT 1";
        $user_login_info_stm = $db->prepare($user_login_info_query);
        $user_login_info_stm->execute();
        $results = $user_login_info_stm->fetchAll();
        $user_login_info_stm->closeCursor();

        if (count($results) > 0) { // if user exists
            if ($results[0]['userPassword'] == $password) {
                $_SESSION['userNum'] = $results[0]['userNum'];
                $_SESSION['userRealname'] = $results[0]['userRealname'];
                $_SESSION['userType'] = $results[0]['userType'];
                //$_SESSION['success'] = "You are now logged in";
                //echo "Wehaaa";
                if ($results[0]['userType'] == $user_type_client) {
                    echo "I'm Client";
                    header('location: TripList.php');
                } elseif ($results[0]['userType'] == $user_type_manager) {
                    echo "I'm Manager";
                    header('location: TripNotReleased.php');
                }
            } else {
                array_push($errors, "Password is Wrong!");
            }
        } else {
            array_push($errors, "User doesn't exist");
        }
    }
    $err_text = end($errors); //Get the last error message
}
?>


<html>
    <head>
        <title>Organized Trips - Mary 2020</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <style>
            body{

                align-items: center;
            }
            h1{
                color:slategrey;
                text-align: center;
                font-size: 50px;
            }
            #index{
                text-align: center;
                display: block;
                font-size: 22px;
            }
            #form{
                text-align: center;
                font-size: 26px;
            }

            button{
                padding: 7px;
                padding-right: 27px;
                padding-left: 27px;
            }
            label{
                display: none;
                width: 150px;
                text-align: left;
            }
            input{
                background-color: whitesmoke;
                width: 450px;
                font-size: 20px;
                padding-top:20px;
                padding-left:4px;
                padding-bottom:10px;
                text-align: left;
                color: black;
            }
            .number{
                display: none;
                padding: 10px;
                color: burlywood;
            }
            fieldset{
                border: none;
                text-align: center;
            }
            legend{
                color:grey;
            }
            button{
                margin: 3px;
                background: slategrey;
                color: burlywood;
                font-weight: 900;
                cursor: pointer;
            }
            #signup{
                padding: 2px;
                background-color: whitesmoke;
            }
            #signup button{
                margin-top: 10px;
            }
            #errormsg{
                color: red;
                text-align: center;
            }
        </style>

    </head>
    <body>
        <br><br><br>
        <h1>Welcome to Mary Travel Agency</h1>
        <a id="index" href="index.html">index.html</a>
        <div id='form'>
            
            <form action="LOGIN.php" method="post">
                <br><br><br>
                <fieldset>
                    <legend><span class="number">*</span>Login Page</legend>
                    <br><p id="errormsg">
                        <?php require_once 'error.php' ?>
                    </p>
                    <label for="email">Email:</label>
                    <input type="text" id="email" name="user_email" placeholder="Email">
                    <br><br>
                    <label for="passwrod">Password:</label>
                    <input type="password" id="password" name="user_password" placeholder="Password">
                    <br>
                </fieldset>

                <button type="submit" name="login">Login</button>
                <button type="reset">Clear</button>
                <br><br>
            </form>
            <div id='signup'>
                <br>
                Don't have an Account?<br>
                <a href="NewUser.php">
                    <button type="button">Sign Up</button></a>
                <br><br>
            </div>

        </div>
    </body>
</html>

