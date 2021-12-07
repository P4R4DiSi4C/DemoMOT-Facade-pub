<?php
session_start();
/**
 * Author: carvalhoda
 * Date: 29.02.2016
 * Summary: File for the post of the admin login page
 */

include "adminClass.php";
$adminClass = new adminClass();

//Check if the username and password are set and not empty
if (!empty($_POST['adminMail']) && !empty($_POST['adminPassword']) && isset($_POST['adminMail'], $_POST['adminPassword']))
{
    //Check if the mail matches the pattern
    if (preg_match($adminClass->emailPattern, $_POST['adminMail']))
    {
        //Call the login method
        $adminClass->loginAdmin($_POST['adminMail'], $_POST['adminPassword']);
    }
    else
    {
        //Redirect the user to a login page with a pattern error
        header("location:login.php?patternError");
    }
}
else
{
    //Display an error if we don't have the mail/password input
    header("location:login.php?missingValues");
}

?>
