<?php
session_start();
/**
 * Author: carvalhoda
 * Date: 29.02.2016
 * Summary: File for the post of the loginpage
 */
//Include the class
include "ledClass.php";

//Instance the class
$class = new ledClass();

//Check if the username and password are set and not empty
if(!empty($_POST['inputEmail']) && !empty($_POST['inputPassword']) && isset($_POST['inputEmail'],$_POST['inputPassword']))
{
    //Check if the mail matches the pattern
    if(preg_match($class->emailPattern,$_POST['inputEmail']))
    {
        //Call the login method
        $class->Login($_POST['inputEmail'], $_POST['inputPassword']);
    }
    else
    {
        //Redirect the user to a login page with a pattern error
        header("location:loginPage.php?patternError");
    }
}
else
{
    //Display an error if we don't have the mail/password input
    header("location:loginPage.php?missingValues");
}
?>
