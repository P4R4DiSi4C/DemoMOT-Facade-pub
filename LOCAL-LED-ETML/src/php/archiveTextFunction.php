<?php
session_start();
/**
 * Author: carvalhoda
 * Date: 29.02.2016
 * Summary: Page that contains the function to call the logout function
 */
//Include the class
include "ledClass.php";

//Instance the class
$class=new ledClass();

//Call the method to delete messages
$class->deleteMessages();
?>
