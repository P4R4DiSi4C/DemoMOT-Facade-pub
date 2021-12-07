<?php
session_start();
/**
 * Author: carvalhoda
 * Date: 29.02.2016
 * Summary: Page that contains the function to call the logout function
 */
//Include the class
include "adminClass.php";

//Instance the class
$class=new adminClass();

//Call the logout function
$class->Logout();
?>
