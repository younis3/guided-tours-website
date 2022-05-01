<?php

//Variables
$user_type_client = "ל";
$user_type_manager = "מ";

$err_text = "";

$arrayTargetedAudience = array("Please select a target audience", "Well-wishers", "Families with children",
    "Suitable for everyone", "Retirees", "Looking for a friend", "After the army");



//Connect to DB
try {
    $db = new PDO('mysql:host=localhost;dbname=travelagencydb', 'root', '');
    //$db->exec("set NAMES utf8");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException$ex) {
    //$err_text = $ex->getMessage();
    //include('error.php');
    exit();
}


//Start Session
if (!isset($_SESSION)) {
    session_start();
} 



//This function to convert Y/N from Database to Yes/No to view on the table
function func_kosher($answer){
    $yes_char_hebrew = "כ";
    if ($answer == "Y" || $answer == $yes_char_hebrew) {
        return "Yes";
    } else {
        return "No";
    }
}

