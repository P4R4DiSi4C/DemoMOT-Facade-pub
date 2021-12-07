<?php
session_start();
/**
 * Author: carvalhoda
 * Date: 29.02.2016
 * Summary: File for the post of the loginpage
 */
//Include the class
include "ledClass.php";

//Check if the id of the text is set, the color, speed and figure
if(isset($_POST['idText'],$_POST['colorPicker'],$_POST['speedList'],$_POST['figList']))
{
    //Instance the class
    $class = new ledClass();

    //Check if the text exists to prevent any alteration
    if($class->textExists($_POST['idText']))
    {
        //Check again if the user has the rights to use this text
        if ($class->checkIfRightsToUse($_POST['idText']))
        {
            //Count the length of the text
            $count = strlen($class->getTextWithID($_POST['idText']));

            //Check if the text has a length lower than 5 chars and if the values have been altered
            //If the values have been altered with the dev console we redirect the user to the previous page with an error
            if (($count < 5 && $_POST['speedList'] != 0) || ($count < 5 && $_POST['figList'] != 1))
            {
                //Redirect to the messages page with a displayed error
                header("location: messages.php?sendMessageError");
            }
            else
            {
                //Check if the text has a length bigger than 5 chars and if the values have been altered
                //If the values have been altered with the dev console we redirect the user to the previous page with an error
                if (($count > 5 && ($_POST['speedList'] < 1 || $_POST['speedList'] > 5)) || ($count > 5 && $_POST['figList'] == 1))
                {
                    //Redirect to the messages page with a displayed error
                    header("location: messages.php?sendMessageError");
                }
                else
                {
                    //Check if the value is empty
                    if (empty($_POST['colorPicker']))
                    {
                        //Set a default value
                        $_POST['colorPicker'] = "rgb(255, 0, 0)";
                    }

                    //Take each rgb value and make an array
                    $rgb = sscanf($_POST['colorPicker'], "rgb(%d, %d, %d)");

                    //Convert RGB to HEX value
                    $hex = $class->rgb2hex($rgb[0], $rgb[1], $rgb[2]);

                    //Set the variables
                    $class->setTime($class->getFutureMesDate());

                    //Set the color, figure and speed setters.
                    $class->setColor($hex);
                    $class->setFigureMessage($_POST['figList']);
                    $class->setSpeedMessage($_POST['speedList']);

                    //Get the text with his id
                    $text = $class->getTextWithID($_POST['idText']);

                    //Set the text setter
                    $class->setText($text);

                    //Call the method to finally add the message to the db
                    $class->addMessage();
                }
            }
        }
        else
        {
            //If he doesn't have the right to use this text, we redirect him to the messages page again and display a toast with an error
            header("location:messages.php?noRights");
        }
    }
    else
    {
        //If the text doesnt exists, that would say that the user altered the value with the dev console
        header("location: messages.php?sendMessageError");
    }
}
else
{
    echo'Error: Missing values';
}
?>
