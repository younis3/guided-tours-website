<?php
require_once 'mydbfuncs.php';


$errors = array();
$name = $email = $password1 = $password2 = ""; //for first load of the page

if(isset($_POST['clear_user'])){
    $name = $email = $password1 = $password2 = ""; //to clear all the form values
}

// ADD NEW USER
if (isset($_POST['new_user'])) {

    // receive all input values from the form  
    $name = filter_input(INPUT_POST, "user_name");
    $email = filter_input(INPUT_POST, "user_email");
    $password1 = filter_input(INPUT_POST, "user_password");
    $password2 = filter_input(INPUT_POST, "user_password2");


    // form validation: ensure that the form is correctly filled ...
    // by adding (array_push()) corresponding error unto $errors array
    if (empty($name)) {
        array_push($errors, "Please Enter Your Name");
    } elseif (empty($email)) {
        array_push($errors, "Please Enter Your Email");
    } elseif (empty($password1)) {
        array_push($errors, "Password is required");
    } elseif ($password1 != $password2) {
        array_push($errors, "The Passwords Do Not Match");
    }   
    
    /* check the database to make sure 
      user does not already exist with the same email
     */
    $user_check_query = "SELECT * FROM tblusers WHERE userEmail='$email' LIMIT 1";
    $user_check_stm = $db->prepare($user_check_query);
    $user_check_stm->execute();
    $results = $user_check_stm->fetchAll();
    $user_check_stm->closeCursor();
    if (count($results) > 0) { // if user exists
        array_push($errors, "Email already exists");
    }
    
    $err_text = end($errors); //Get the last error message


    
    // Finally, register user if there are no errors in the form
    if (count($errors) == 0) {
  	$query_new_user = "INSERT INTO tblusers (userEmail, userPassword, userRealname) VALUES (:userEmail, :userPassword, :userRealname)";       
        $stm_new_user = $db->prepare($query_new_user);
        $stm_new_user->bindValue(':userEmail', $email);
        $stm_new_user->bindValue(':userPassword', $password1);
        $stm_new_user->bindValue(':userRealname', $name);
        $execute_success = $stm_new_user->execute();
        $stm_new_user->closeCursor();
        if ($execute_success){
            header('location: LOGIN.php');
        }
        else{
            echo "ERROR";
        }

  	
    }
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
            #login{
                padding: 2px;
                background-color: whitesmoke;
            }
            #login button{
                margin-top: 10px;
            }
            #errormsg{
                color: red;
                text-align: center;
            }
        </style>

    </head>
    <body>
        <br><br><br><br>

        <div id='form'>

            <form action="" method="post">

                <br>
                <fieldset>
                    <legend><span class="number">*</span>Sign Up Page</legend>
                    <br><p id="errormsg">
                        <?php require_once 'error.php'?>
                    </p><br>
                    <label for="name">Your Name:</label>
                    <input type="text" id="name" name="user_name" placeholder="Your Name" value="<?php echo $name;?>">
                    <br><br>   
                    <label for="email">Email:</label>
                    <input type="text" id="email" name="user_email" placeholder="Email"  value="<?php echo $email;?>">
                    <br><br>
                    <label for="passwrod">Password:</label>
                    <input type="password" id="password" name="user_password" placeholder="Password">
                    <br><br>
                    <label for="passwrod">Confirm Password:</label>
                    <input type="password" id="password2" name="user_password2" placeholder="Confirm Password">
                    <br>
                </fieldset>

                <button type="submit" name="new_user">Sign Up</button>
                <button type="submit" name="clear_user">Clear</button>
                <br><br>
            </form>
            <div id='login'>
                <br>
                Already have an Account?<br>
                <a href="LOGIN.php">
                    <button type="button">Login</button></a>
                <br><br>
            </div>

        </div>
    </body>
</html>

