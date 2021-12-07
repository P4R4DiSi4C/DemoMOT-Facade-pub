<?php
session_start();
?>
<?php
/**
 * Author: carvalhoda
 * Date: 03.11.2016
 * Summary: File for the post of the resetPassword page
 */
//Include the class
include "ledClass.php";

//Instance the class
$class = new ledClass();

//Check if we have the required informations to validate the account
//Passwords to verify if they corresponds to themselves, the ID of the user to confirm and the token to verify
if(!empty($_POST['inputPW']) && !empty($_POST['confirmPW']) && !empty($_SESSION['idToConfirm']) && !empty($_SESSION['tokenToConfirm']) && isset($_POST['inputPW'],$_POST['confirmPW'],$_SESSION['idToConfirm'],$_SESSION['tokenToConfirm']))
{
    //Check if passwords corresponds
    if($_POST['inputPW'] == $_POST['confirmPW'])
    {
        //Call the method to confirm the acc
        $class->resetPasswordInDb($_POST['inputPW']);
    }
    else
    {
        //Redirect to a page with an error that passwords doesn't matches
        header("location:displayMessages.php?pwNoMatch");
    }
}
else
{
    //Redirect to a page with a missing infos error message
    header("location:displayMessages.php?missingInfos");
}
?>
