<?php
session_start();
/**
 * Author: carvalhoda
 * Date: 03.11.2016
 * Summary: File for the post of the forgot password post
 */
//Include the class
include "ledClass.php";

//Check if the mail is set and not empty
if(!empty($_POST['inputEmail']) && isset($_POST['inputEmail']))
{
    //Instance the class
    $class = new ledClass();

    //Check if the email corresponds to the pattern
    if(preg_match($class->emailPattern,$_POST['inputEmail']))
    {

        //Call method to check if the mail exists in the database and then call the method to begin the register process.
        if ($class->checkMail($_POST['inputEmail'],1))
        {
            $class->resetPassword($_POST['inputEmail']);
        }
        else
        {
            //Redirect the user to a page with an error not in database
            header("location:forgotPassword.php?notInDb");
        }
    }
    else
    {
        //Redirect the user with an error, string doesnt matches pattern
        header("location:forgotPassword.php?patternError");
    }
}
else
{
    //Display an error if we don't have the mail input
    header("location:forgotPassword.php?missingMail");
}
?>
