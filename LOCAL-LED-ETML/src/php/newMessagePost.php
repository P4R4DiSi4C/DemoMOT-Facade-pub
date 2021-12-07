<?php
session_start();
/**
 * Author: carvalhoda
 * Date: 29.02.2016
 * Summary: File for the post of new message
 */
//Include the class
include "ledClass.php";

//Instance the class
$class = new ledClass();

//Check if we got a text and that it's not empty
if(!empty($_POST['textMsg']) && isset($_POST['textMsg']))
{
    //Check the length of the message
    if (strlen($_POST['textMsg']) < 56)
    {
        //Check if the text matches the regex and if doesn't contains only white spaces
        if(preg_match($class->textPattern,$_POST['textMsg']) && (strlen($_POST['textMsg']) > 0 && strlen(trim($_POST['textMsg'])) > 0))
        {
            //Call the method to add the text to the database
            $class->addText($_POST['textMsg']);
        }
        else
        {
            //Redirect the user to a page with a pattern error message
            header("location: messages.php?patternError");
        }
    }
    else
    {
        //If the message is too long we display an error
        header("location: messages.php?addTxtTooLong");
    }
}
else
{
    //Si pas de message, alors erreur
    header("location: messages.php?addNoText");
}
?>
