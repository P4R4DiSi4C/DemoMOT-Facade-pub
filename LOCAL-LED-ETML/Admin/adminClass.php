<?php
/**
 * Created by PhpStorm.
 * User: carvalhoda
 * Date: 13.06.2016
 * Time: 13:18
 */

//Import the library to be able to use password_* methods from higher php versions
require "../ressources/lib/password.php";

class adminClass
{
    //Pattern for the email
    public $emailPattern = "/^[a-z]+@(etml|vd).educanet2.ch$/";


    /**
     * Main constructor of the class
     * Default constructor
     */
    public function __construct() {
        $this->dbh = new PDO('mysql:host=localhost;dbname=db_etml_leds;charset=utf8', "adminleds", "admin-44",array(PDO::ATTR_PERSISTENT => true,PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
    }

    /**
     * Main destructor of the class
     * Default destructor
     */
    public function __destruct()
    {
        $this->dbh = null;
    }

    /*
    * Method used to get the most recent message
    */
    function getRecentMessages()
    {
        //Prepare the select request
        $stmt = $this->dbh->prepare("SELECT useUsername,mesTimePassage,mesMessage FROM t_message,t_user WHERE fkUser = idUser ORDER BY mesTimePassage DESC LIMIT 10");

        //Execute the request
        $stmt->execute();

        //Fetch the result in the array
        $result = $stmt->fetchAll();

        return $result;
    }

    /**
     * Method to transform the hour to elapsed time
     * @param $time
     * @return string
     */
    function humanTiming ($time)
    {

        $time = time() - $time; // to get the time since that moment
        $time = ($time<1)? 1 : $time;
        $tokens = array (
            31536000 => 'an',
            2592000 => 'mois',
            604800 => 'semaine',
            86400 => 'jour',
            3600 => 'heure',
            60 => 'minute',
            1 => 'seconde'
        );

        foreach ($tokens as $unit => $text) {
            if ($time < $unit) continue;
            $numberOfUnits = floor($time / $unit);
            return $numberOfUnits.' '.$text.(($numberOfUnits>1)?'s':'');
        }

    }

    /**
     * Get the user with most messages
     */
    function userMostMessages()
    {
        //Get the user with most messages
        $stmt = $this->dbh->prepare("SELECT useUsername, COUNT(*) FROM t_message,t_user WHERE fkUser = idUser GROUP BY fkUser ORDER BY 2 DESC LIMIT 1");

        //Execute the request
        $stmt->execute();

        //Fetch the result in the array
        $result = $stmt->fetchAll();

        return $result;
    }


    /*
     * Count all the messages in the database
     */
    function countAllMessages()
    {
        //Count all the messages in db
        $stmt = $this->dbh->prepare("SELECT COUNT(*) FROM t_message");

        //Execute the request
        $stmt->execute();

        //Fetch the result in the array
        $result = $stmt->fetchAll();

        return $result;
    }

    /*
     * Count all the messages from today
     */
    function countNewMessages()
    {
        //Get messages from today only
        $stmt = $this->dbh->prepare("SELECT COUNT(*) FROM t_message WHERE mesTimePassage >= CURDATE()");

        //Execute the request
        $stmt->execute();

        //Fetch the result in the array
        $result = $stmt->fetchAll();

        return $result;
    }

    /*
    * Get the most used color
    */
    function mostUsedColor()
    {
        //Get the most used color
        $stmt = $this->dbh->prepare("SELECT mesColor, COUNT(*) FROM t_message GROUP BY mesColor ORDER BY 2 DESC LIMIT 1");

        //Execute the request
        $stmt->execute();

        //Fetch the result in the array
        $result = $stmt->fetchAll();

        return $result;
    }

    /*
     * Get the nb of activated accounts
     */
    function getActivatedAccs()
    {
        //Get the nb of activated accs
        $stmt = $this->dbh->prepare("SELECT COUNT(*) FROM t_user WHERE useStatus = 1");

        //Execute the request
        $stmt->execute();

        //Fetch the result in the array
        $result = $stmt->fetchAll();

        return $result;
    }

    /*
    * Get the nb of activated accounts
    */
    function getActivatedAccsDetails()
    {
        //Get the nb of activated accs
        $stmt = $this->dbh->prepare("SELECT * FROM t_user WHERE useStatus = 1");

        //Execute the request
        $stmt->execute();

        //Fetch the result in the array
        $result = $stmt->fetchAll();

        return $result;
    }

    /*
     * Get the nb of total users
     */
    function getNbUsers()
    {
        //Get the nb of activated accs
        $stmt = $this->dbh->prepare("SELECT COUNT(*) FROM t_user");

        //Execute the request
        $stmt->execute();

        //Fetch the result in the array
        $result = $stmt->fetchAll();

        return $result;
    }

    /*
     * Function to return true/false if the user is admin or not
     */
    function isAdmin()
    {
        $id = $_SESSION['id'];

        //Get the useIsAdmin entity in db to see if he's an admin
        $stmt = $this->dbh->prepare("SELECT useIsAdmin FROM t_user WHERE idUser = '$id'");

        //Execute the request
        $stmt->execute();

        //Fetch the result in the array
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        //Check if he's admin or not
        if($result[0]['useIsAdmin'] == 1)
        {
            return true;
        }
        else
        {
            return false;
        }
    }


    /**
     * @param $username
     * @param $password
     * Get an username and a password given by the admin and check everything before logging him
     */
    public function loginAdmin($username,$password)
    {
        //Prepare the select statement to verify later if the password corresponds
        $stmt = $this->dbh->prepare("SELECT idUser,useStatus,usePassword FROM t_user WHERE useUsername='$username'");

        //Execute the statement
        $stmt->execute();

        //Count the nb of the rows, if there's 1 line, we got a password
        $count=$stmt->rowCount();

        //Set a variable for the password in the database to 0, for later.
        $dbPassword = 0;

        //Set default value
        $userStatus = 0;

        //Set default value
        $id = 0;

        //Check if we got 1 line in the rowcount
        if($count == 1)
        {
            //Fetch the result of the statement
            $result = $stmt->fetchAll();

            //Get the password from database and set it
            foreach($result as $row)
            {
                $dbPassword = $row['usePassword'];
                $userStatus = $row['useStatus'];
                $id = $row['idUser'];
            }

            //Check if password corresponds to the one given by the user
            if(password_verify($password,$dbPassword))
            {
                if($userStatus == 1)
                {
                    //Initiate a session for the user with his username and one with is id
                    $_SESSION['username'] = $username;
                    $_SESSION['id'] = $id;

                    //CHECK IF ADMIN
                    if($this->isAdmin())
                    {
                        //Redirect the user with a successfull message in the index page
                        header("location:index.php");
                    }
                    else
                    {
                        session_destroy();
                        header("location:login.php?notAdmin");
                    }
                }
                else
                {
                    //Redirect the user to a page with an error message
                    header("location:registerPage.php?notActivated");
                }
            }
            else
            {
                //Check if password is null
                if($dbPassword == "" || $userStatus == 0)
                {
                    //Redirect the user to a page with an error message
                    header("location:registerPage.php?notActivated");
                }
                else
                {
                    //Refresh the login page with an error message
                    header("location:login.php?pwError");
                }
            }
        }

        //If we didn't get 1 line in rowcount it means the user doesn't exist
        else
        {
            //Refresh the login page with an error message
            header("location:login.php?accNotFound");
        }
    }


    /*
     * Method used to display an error or success message
     */
    function displayErrorSuccessAlert($errOrSuc,$message)
    {
        //Define a constant for each type of message
        define("ERROR_MESSAGE",     "0");
        define("SUCCESS_MESSAGE",     "1");

        //Success message
        if($errOrSuc == SUCCESS_MESSAGE)
        {
            return ("<div class='alert alert-success' id='alert'>
                        <strong>Succès!</strong> $message
                    </div>");
        }

        //Error message
        if($errOrSuc == ERROR_MESSAGE)
        {
            return ("<div class='alert alert-danger' id='alert'>
                      <strong>Erreur:</strong> $message
                    </div>");
        }

        return NULL;
    }

    /*
    * Method used to log out the user and destroy his session
    */
    public function Logout()
    {
        //Check if the $_SESSION is set
        if (isset($_SESSION['username']))
        {
            //Destroy the session
            session_destroy();

            //Redirect the user to the login page
            header("location:login.php");
        }

        //If the $_SESSION isn't set we redirect the user to the index
        else
        {
            //Redirect the user to the index
            header("location:index.php");
        }
    }

    /**
     * @param $id
     * Method that gets the id of the user and promotes him admin
     */
    public function PromoteAdmin($id)
    {
        //Prepare the select
        $stmt = $this->dbh->prepare("UPDATE t_user SET useIsAdmin=1 WHERE idUser = '$id'");

        //Execute the statement
        $stmt->execute();

        header("location: accounts.php");
    }

    /**
     * @param $id
     * Method thats gets the id of the admin to revoke his admin rights
     */
    public function RevokeAdmin($id)
    {
        //Prepare the select
        $stmt = $this->dbh->prepare("UPDATE t_user SET useIsAdmin=0 WHERE idUser = '$id'");

        //Execute the statement
        $stmt->execute();

        header("location: accounts.php");
    }

    /**
     * @param $id
     * Method that gets the id and reset the acc
     */
    public function ResetAccount($id)
    {
        //Prepare the select
        $stmt = $this->dbh->prepare("UPDATE t_user SET useStatus = 0, useToken = '', usePassword = '' WHERE idUser = '$id'");

        //Execute the statement
        $stmt->execute();

        header("location: accounts.php");
    }

    /**
     * @param $id
     * @return bool
     * Method that gets the id of the user and checks if he's admin
     */
    public function AccountIsAdmin($id)
    {
        //Prepare the select
        $stmt = $this->dbh->prepare("SELECT useUsername FROM t_user WHERE idUser = '$id' AND useIsAdmin = 1");

        //Execute the statement
        $stmt->execute();

        if($stmt->rowCount() == 1)
        {
            return true;
        }
        else
        {
            return false;
        }
    }


    public function liveSearch($key)
    {
        $stmt=$this->dbh->prepare("SELECT useUsername FROM t_user where useUsername LIKE '%{$key}%'");

        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }
}