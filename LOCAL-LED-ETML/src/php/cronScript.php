<?php
session_start();
/**
 * Author: carvalhoda
 * Date: 29.02.2016
 * Summary:
 */
//Include the class
include "ledClass.php";

//Instances the class
$class = new ledClass();

//Check if there is nothing to display soon
if(!$class->anythingToDisplay())
{
    //Get a random message from the table message and send it
    $class->randomDisplay();

    //Send directly the new random message
    $class->SendCurl();
}
else
{
    //call the method to get and send the message to the display
    $class->SendCurl();
}
?>
