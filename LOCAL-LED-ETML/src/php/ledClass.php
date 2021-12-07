<?php
/**
 * Created by PhpStorm.
 * User: carvalhoda
 * Date: 30.05.2016
 * Time: 15:34
 */

/**
 * Main class of the project
 * Class ledClass
 */

//Import the library to be able to use password_* methods from higher php versions
require "../../ressources/lib/password.php";
//DEFINE INTERVAL OF MESSAGES
define("INTERVAL", 3600);
define("INTERVALRANDOM", 20);

class ledClass
{
    //Variable for the color
    public $color;

    //Variable for the text
    public $text;

    //Variable for the speed of the message
    public $speedMessage;

    //Variable for the figure of the message
    public $figureMessage;

    //Variable for the time of the message
    public $time;

    //Pattern for the email
    public $emailPattern = "/^[a-z.]+@(etml|vd).educanet2.ch$/";

    //Pattern for a message
    public $textPattern = "/^[a-zA-Z -!?0-9']+$/";

    /**
     * @return mixed
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * @param mixed $color
     */
    public function setColor($color)
    {
        $this->color = $color;
    }

    /**
     * @return mixed
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param mixed $message
     */
    public function setText($text)
    {
        $this->text = $text;
    }

    /**
     * @return mixed
     */
    public function getSpeedMessage()
    {
        return $this->speedMessage;
    }

    /**
     * @param mixed $speedMessage
     */
    public function setSpeedMessage($speedMessage)
    {
        $this->speedMessage = $speedMessage;
    }

    /**
     * @return mixed
     */
    public function getFigureMessage()
    {
        return $this->figureMessage;
    }

    /**
     * @param mixed $figureMessage
     */
    public function setFigureMessage($figureMessage)
    {
        $this->figureMessage = $figureMessage;
    }

    /**
     * @return mixed
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * @param mixed $time
     */
    public function setTime($time)
    {
        $this->time = $time;
    }



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

    /**
     * Method to retrieve all the messages of the user in the database
     */
    public function getUserMessages()
    {
        if(isset($_SESSION['username']))
        {
            //Get the username
            $username = $_SESSION['username'];

            //Prepare the select request
            $stmt = $this->dbh->prepare("SELECT idText,texMessage,fkUser FROM t_text,t_user WHERE useUsername = '$username' AND fkUser = idUser AND texIsArchived = 0 ORDER BY idText DESC");

            //Execute the request
            $stmt->execute();

            //Fetch the result in the array
            $result = $stmt->fetchAll();

            //Count the nb of rows we got
            $count = $stmt->rowCount();

            //If we got more than one result we create a table
            if($count > 0)
            {
                // Create the beginning of HTML table, and the first row with colums title
                $messages_table = '<table id="messagesTable" class="responsive-table"><thead class="etmlcolor"><tr><th>Choix</th><th>Texte</th><th>Supprimer</th></tr></thead><tbody>';

                // Parse the result set, and adds each row and colums in HTML table
                foreach ($result as $row)
                {
                    //Set the id of the text to a a variable
                    $id = $row['idText'];

                    $messages_table .= '<tr>
                                    <td>' . "
                                        <input name='mesGroup' type='radio' id='$id' value='$id' />
                                        <label for='$id'></label>" . '
                                    </td>
                                    <td>
                                        <input id=' . $id . ' name="messageInput" type="text" class="col s12 m6" disabled readOnly value="' . htmlspecialchars($row['texMessage']) . '" pattern="^[a-z-A-Z -?!0-9]+$">
                                        <button type="button" id=' . $id . ' name ="modButton" value="' . $id . '" onclick="unlockInput(this)" class="btn-floating btn-small waves-effect waves-light blue">
                                            <i class=material-icons>edit</i>
                                        </button>
                                    </td>
                                    <td>
                                        <input type="checkbox" class="filled-in" id="DeleteCheck[' . $id . ']" name="DeleteCheck[]" value=' . $id . ' />
                                        <label for="DeleteCheck[' . $id . ']">Supprimer</label>
                                    </td>
                                </tr>';
                }
                $messages_table .= '</tbody></table>';// ends the HTML table

                //Display the html table
                return $messages_table;
            }
            else
            {
                //If no result we return a null
                return null;
            }
        }
        else
        {
            return null;
        }
    }

    /*
     * Method to get the pending messages
     */
    public function getPendingMessages()
    {
        //Check if the user is logged in
        if(isset($_SESSION['username']))
        {
            //Prepare the select request
            $stmt = $this->dbh->prepare("SELECT idMessage,mesTimePassage,mesColor,mesSpeed,mesMessage,fkFigure,figData,figMove FROM t_figure,t_message WHERE fkFigure = idFigure ORDER BY mesTimePassage DESC");

            //Execute the request
            $stmt->execute();

            //Fetch the result in the array
            $result = $stmt->fetchAll();

            //Check if we got messages in the table t_message
            if(!empty($result))
            {
                // Create the beginning of HTML table, and the first row with colums title
                $messages_table = '<div style="height:500px; overflow-y: auto;"><table id="pendingTable" class="responsive-table"><thead class="etmlcolor"><tr><th>Message</th><th>Heure de passage</th><th>Vitesse</th><th>Couleur</th><th>Effet</th></tr></thead><tbody>';

                // Parse the result set, and adds each row and colums in HTML table
                foreach ($result as $row)
                {
                    //Format the hour of passage to show only the hours and minutes
                    $mesTimePassage = $row['mesTimePassage'];
                    $timeNow = date("Y-m-d H:i");

                    //Check if the time of the message is bigger than the time "now"
                    if($mesTimePassage > $timeNow)
                    {
                        //Format the time to have only hours and minutes
                        $mesTimePassageFormated1 = strtotime($mesTimePassage);
                        $mesTimePassageFormated2 = date("H:i", $mesTimePassageFormated1);

                        //Add to the table
                        $messages_table .= '<tr>
                                        <td>' . htmlspecialchars($row['mesMessage']) . '</td>
                                        <td>' . $mesTimePassageFormated2 . '</td>
                                        <td>' . htmlspecialchars($row['mesSpeed']) . ' secondes</td>
                                        <td><span style="padding-left: 6em; background-color:' . htmlspecialchars($row['mesColor']) . '"></span></td>
                                        <td>' . htmlspecialchars($row['figMove']) . '</td>
                                    </tr>';
                    }
                }
                $messages_table .= '</tbody></table></div>';// ends the HTML table

                //Display the html table
                echo $messages_table;
            }
            //If the table is empty
            else
            {
                echo'<h4 class="red-text center">Pas de messages en attente d\'affichage</h4>';
            }
        }
    }

    /*
     * Method that will get all the figures in the database and return them
     */
    public function getAllFigures()
    {
        //Prepare the select request
        $stmt = $this->dbh->prepare("SELECT idFigure,figData,figMove FROM t_figure");

        //Execute the request
        $stmt -> execute();

        //Fetch the result in the array
        return $stmt->fetchAll();
    }

    //**************CONVERT RGB TO HEX AND VICE VERSA*******************//

    /*
    *Convert RGB value to Hex
    */
    function rgb2hex($r, $g=-1, $b=-1)
    {
        if (is_array($r) && sizeof($r) == 3)
            list($r, $g, $b) = $r;
        $r = intval($r); $g = intval($g);
        $b = intval($b);
        $r = dechex($r<0?0:($r>255?255:$r));
        $g = dechex($g<0?0:($g>255?255:$g));
        $b = dechex($b<0?0:($b>255?255:$b));
        $color = (strlen($r) < 2?'0':'').$r;
        $color .= (strlen($g) < 2?'0':'').$g;
        $color .= (strlen($b) < 2?'0':'').$b;
        $color ='#'.$color;
        return $color;
    }

    /*
     * Method to add a message to the database
     */
    function addMessage()
    {
        //SQL Request to insert the message
        $sql = "INSERT INTO t_message(
            idMessage,
            mesTimePassage,
            mesColor,
            mesSpeed,
            mesMessage,
            fkFigure,
            fkUser)
            VALUES(
            NULL,
            :mesTimePassage,
            :mesColor,
            :mesSpeed,
            :mesMessage,
            :fkFigure,
            :fkUser)";

        //Prepare the request
        $stmt = $this->dbh->prepare($sql);

        //Get all the values of the message
        $mesMessage = $this->getText();
        $mesTimePassage = $this->getTime();
        $mesColor = $this->getColor();
        $mesSpeed = $this->getSpeedMessage();
        $fkFigure = $this->getFigureMessage();
        $fkUser = $_SESSION['id'];

        //Bind the parameter
        $stmt->bindParam(':mesMessage',$mesMessage, PDO::PARAM_STR);
        $stmt->bindParam(':mesTimePassage', $mesTimePassage);
        $stmt->bindParam(':mesColor', $mesColor, PDO::PARAM_STR);
        $stmt->bindParam(':mesSpeed', $mesSpeed, PDO::PARAM_INT);
        $stmt->bindParam(':fkUser', $fkUser, PDO::PARAM_INT);
        $stmt->bindParam(':fkFigure', $fkFigure, PDO::PARAM_INT);

        //Execute the request
        $stmt->execute();

        //Redirect the user
        header("location:pending.php");
    }

    /*
     * Method to check if a text exists, to prevent an hidden input alteration
     */
    function textExists($idText)
    {
        //Request
        $stmt = $this->dbh->prepare("SELECT texMessage FROM t_text WHERE idText='$idText'");

        //Execute the statement
        $stmt->execute();

        //Count the rows
        $count = $stmt->rowCount();

        //If we got a result we return true
        if($count == 1)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    /*
    * Method to add a new text to the database
    */
    function addText($texMessage)
    {
        //SQL Request to insert the athlete
        $sql = "INSERT INTO t_text(
            idText,
            texMessage,
            texIsArchived,
            fkUser)
            VALUES(
            NULL,
            :texMessage,
            0,
            :fkUser)";

        //Prepare the request
        $stmt = $this->dbh->prepare($sql);

        //Get all the values of the messages
        $fkUser = $_SESSION['id'];

        //Bind the parameter
        $stmt->bindParam(':texMessage',$texMessage, PDO::PARAM_STR);
        $stmt->bindParam(':fkUser', $fkUser, PDO::PARAM_INT);

        //Execute the request
        $stmt->execute();

        //Redirect the user
        header("location:messages.php");
    }

    /*
     * Method to modify the text
     */
    function modifyText($idText,$textToApply)
    {
        //Declare the update request
        $sql = "UPDATE t_text SET
                texMessage = :textToApply
                WHERE idText = :idText";

        //Prepare the request
        $stmt = $this->dbh->prepare($sql);

        //Bind the parameters
        $stmt->bindParam(':textToApply', $textToApply, PDO::PARAM_STR);
        $stmt->bindParam(':idText', $idText, PDO::PARAM_INT);

        //Execute the request
        $stmt->execute();

        //Redirect the user
        header("location:messages.php");
    }

    /*
     * Method to get the text with his ID
     */
    function getTextWithID($id)
    {
        //Request
        $stmt = $this->dbh->prepare("SELECT texMessage FROM t_text WHERE idText='$id'");

        //Execute the statement
        $stmt->execute();

        //Count the rows
        $count = $stmt->rowCount();

        //Check if we got any match
        if($count == 1)
        {
            //Fetch the result
            $result = $stmt->fetchAll();

            //Return the text
            return $result[0][0];
        }

        return null;
    }

    /*
     * Method to get and set the future message datetime
     */
    function getFutureMesDate()
    {
        //Request
        $sqlCompare = "SELECT MAX(mesTimePassage) FROM t_message";

        //Prepare
        $stmt = $this->dbh->prepare($sqlCompare);

        //Execute the request
        $stmt->execute();

        //Fetch the result of the request
        $result = $stmt->fetchAll();

        //Set a var with the time of passage
        $timeOfPassage = $result[0][0];

        //Set the time NOW
        $timeNow = date("Y-m-d H:i:s");

        //Check if there already a message waiting to be displayed
        if($timeOfPassage > $timeNow)
        {
            $maxTimeOfPassage = strtotime($timeOfPassage);

            //Set the next message to appear 2 mins after the last one
            $futureDate = $maxTimeOfPassage+((INTERVAL)*1);

            //Format the final datetime
            $formatedFutureDate = date("Y-m-d H:i:s", $futureDate);

            //Return the final datetime
            return $formatedFutureDate;
        }
        else
        {
            //The message with the biggest date has already been executed

            //Transform the datetime now to add 1 minute
            //SEND THE MESSAGE 1min later if it isn't planned to show a message anytime
            $timeNow = date("Y-m-d H:i:s");

            $nextHour   = date('Y-m-d H:00:s', strtotime('+1 hour', strtotime($timeNow)));
            $nextHour = $nextHour + 5;
            //Return formated date
            return $nextHour;

        }
    }

    /*
    * Method to get and set the future message datetime
    */
    function anythingToDisplay()
    {
        //Request
        $sqlCompare = "SELECT MAX(mesTimePassage) FROM t_message";

        //Prepare
        $stmt = $this->dbh->prepare($sqlCompare);

        //Execute the request
        $stmt->execute();

        //Fetch the result of the request
        $result = $stmt->fetchAll();

        //Set a var with the time of passage
        $timeOfPassage = $result[0][0];

        //Set the time NOW
        $timeNow = date("Y-m-d H:i:s");

        //Check if there already a message waiting to be displayed
        if($timeOfPassage > $timeNow)
        {
            //If there's already something waiting to be display we return true
            return true;
        }
        else
        {
            //If there isn't anything to display we return false;
            return false;
        }
    }

    /*
     * Method used to get a random message and send it
     */
    function randomDisplay()
    {
        //Request
        $query = 'SELECT * FROM t_message ORDER BY RAND() LIMIT 1';

        //Prepare
        $stmt = $this->dbh->prepare($query);

        //Execute the request
        $stmt->execute();

        //Fetch the result of the request
        $result = $stmt->fetchAll();

        //Foreach loop to get each field
        foreach($result AS $row)
        {
            //Get each field and set a var with  each of them
            $mesColorCol = $row['mesColor'];
            $mesSpeedINT = $row['mesSpeed'];
            $mesMessageText = $row['mesMessage'];
            $fkFigureID = $row['fkFigure'];
            $fkUserID = 1080;
        }

        //SQL Request to insert the message
        $sql = "INSERT INTO t_message(
            idMessage,
            mesTimePassage,
            mesColor,
            mesSpeed,
            mesMessage,
            fkFigure,
            fkUser)
            VALUES(
            NULL,
            :mesTimePassage,
            :mesColor,
            :mesSpeed,
            :mesMessage,
            :fkFigure,
            :fkUser)";


        //Get the time where the message has to be display
        //Set the time NOW
        $timeNow = date("Y-m-d H:i:s");
        $currentDate = strtotime($timeNow);
        $futureDate = $currentDate+(INTERVALRANDOM*1);
        $formatedFutureDate = date("Y-m-d H:i:s", $futureDate);


        //Prepare the request
        $stmt = $this->dbh->prepare($sql);

        //Get all the values of the message
        $mesMessage = $mesMessageText;
        $mesTimePassage = $formatedFutureDate;
        $mesColor = $mesColorCol;
        $mesSpeed = $mesSpeedINT;
        $fkFigure = $fkFigureID;
        $fkUser = $fkUserID;

        //Bind the parameter
        $stmt->bindParam(':mesMessage',$mesMessage, PDO::PARAM_STR);
        $stmt->bindParam(':mesTimePassage', $mesTimePassage);
        $stmt->bindParam(':mesColor', $mesColor, PDO::PARAM_STR);
        $stmt->bindParam(':mesSpeed', $mesSpeed, PDO::PARAM_INT);
        $stmt->bindParam(':fkUser', $fkUser, PDO::PARAM_INT);
        $stmt->bindParam(':fkFigure', $fkFigure, PDO::PARAM_INT);

        //Execute the request
        $stmt->execute();
    }

    /*
     * Get the message to display
     */
    function getMessageToDisplay()
    {
        $query = "SELECT mesColor, mesSpeed, mesMessage
                  FROM t_message
                  WHERE mesTimePassage > NOW()
                  ORDER BY mesTimePassage
                  LIMIT 1";

        //Prepare
        $stmt = $this->dbh->prepare($query);

        //Execute the request
        $stmt->execute();

        //Fetch the result of the request
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        //Get the color in hexadecimal of the message
        $color = $result[0]['mesColor'];

        //*********CONVERT THE COLOR TO RGB**********//
        $hex = str_replace("#", "", $color);

        if(strlen($hex) == 3)
        {
            $r = hexdec(substr($hex,0,1).substr($hex,0,1));
            $g = hexdec(substr($hex,1,1).substr($hex,1,1));
            $b = hexdec(substr($hex,2,1).substr($hex,2,1));
        }
        else
        {
            $r = hexdec(substr($hex,0,2));
            $g = hexdec(substr($hex,2,2));
            $b = hexdec(substr($hex,4,2));
        }
        //*******************************************//

        //Unset the index in the array
        unset($result[0]['mesColor']);

        //Add an index for each color in the array
        $result[0]['red'] = $r;
        $result[0]['green'] = $g;
        $result[0]['blue'] = $b;

        //Return the array
        return $result;
    }

    /*
     * Method to get the message to display and send to the display with cUrl
     */
    function SendCurl()
    {
        //Get the details of the message to display
        $result = $this->getMessageToDisplay();


        //Create an array that will contain the fields of the form
        $field_vals = array();

        //Append the field value we set ourselves to the list
        $field_vals['phrase'] = $result[0]['mesMessage'];
        $field_vals['rouge'] = $result[0]['red'];
        $field_vals['vert'] = $result[0]['green'];
        $field_vals['bleu'] = $result[0]['blue'];
        $field_vals['delta'] = $result[0]['mesSpeed'];
        $field_vals['pass'] = 'ETML';
        $field_vals['end'] = '&';

        //Create the "url" for the post
        $postfields = http_build_query($field_vals);

        // open connection
        $ch = curl_init();

        // POST the data to the form submission url
        $submit_url = '172.16.38.21';

        // set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $submit_url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
        //curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
        //curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);


        //execute post (returns TRUE on success or FALSE on failure)
        $result = curl_exec($ch);

        //Return the result of the curl
        echo $result;

        // close connection
        curl_close($ch);
    }

    /*
     * Method to delete the messages
     */
    function deleteMessages()
    {
        //Get the array of ids to delete
        $array = $_SESSION['idsArray'];

        if($this->checkIfRightsToDelete())
        {
            //SQL Request to archive them
            $sql = "UPDATE t_text SET texIsArchived = 1 WHERE idText IN ('".join("','",$array)."')";

            //Prepare the request
            $stmt2 = $this->dbh->prepare($sql);

            //Execute the request
            $stmt2->execute();

            //Unset the session used for the ids to delete
            unset($_SESSION['idsArray']);

            //Redirect the user
            header("location:messages.php");
        }
        else
        {
            //Unset the session used for the ids to delete
            unset($_SESSION['idsArray']);

            //Redirect the user
            header("location:messages.php?Error");
        }
    }


    //////**********************DOWN BELOW YOU'VE GOT ALL THE VERIFICATIONS/CHECKS TO THE ACCOUNT AND THE METHODS
    //////**********************TO SEND THE MAIL,CHECK THE TOKENS ETC...

    /**
     * @param $username
     * @param $password
     * Get an username and a password given by the user and logged him in
     */
    public function Login($username,$password)
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

                    //Redirect the user with a successfull message in the index page
                    header("location:index.php");
                }
                else
                {
                    //Refresh the login page with an error message
                    header("location:registerPage.php?notActivated");
                }
            }
            else
            {
                //Check if password is null
                if($dbPassword == "" || $userStatus == 0)
                {
                    //Refresh the login page with an error message
                    header("location:registerPage.php?notActivated");
                }
                else
                {
                    //Refresh the login page with an error message
                    header("location:loginPage.php?pwError");
                }
            }
        }

        //If we didn't get 1 line in rowcount it means the user doesn't exist
        else
        {
            //Refresh the login page with an error message
            header("location:loginPage.php?accNotFound");
        }
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

            //Redirect the user to the index with a success message
            header("location:loginPage.php");
        }

        //If the $_SESSION isn't set we redirect the user to the index with an error message
        else
        {
            //Redirect the user to the index with an error message
            header("location:index.php");
        }
    }

    /*
     * Method to check if an user hasn't altered any values of the checkbox to delete and prevent
     * a deletion of another user text.
     */
    function checkIfRightsToDelete()
    {
        if(isset($_SESSION['id'],$_SESSION['idsArray']))
        {
            //Get the array of ids to delete
            $array = $_SESSION['idsArray'];

            //Get the id of the user
            $id = $_SESSION['id'];

            //Verification if the "fkUser" corresponds to the different values
            //If not, it means he modified a value without the rights, so we pop an error message
            $sqlVerif = "SELECT idText FROM t_text WHERE fkUser ='$id' AND idText IN ('".join("','",$array)."')";

            //Prepare the request for the verification
            $stmt = $this->dbh->prepare($sqlVerif);

            //Execute the request
            $stmt->execute();

            //Get the nb of rows that we got
            $count = $stmt->rowCount();

            if($count == count($array))
            {
                //Return true if we has the rights
                return true;
            }
            else
            {
                //Return false if not
                return false;
            }
        }
        return false;
    }

    /*
     * Method to check if the user hasn't made any alteration to the value of the radiobutton
     * to prevent any use of another user text
     */
    function checkIfRightsToUse($idText)
    {
        //Check if we got the values of session needed
        if(isset($_SESSION['id']))
        {
            //Get the id
            $id = $_SESSION['id'];

            //SQL for the verification
            $sqlVerif = "SELECT texMessage FROM t_text WHERE idText ='$idText' AND fkUser = '$id'";

            //Prepare the request
            $stmt = $this->dbh->prepare($sqlVerif);

            //Execute the request
            $stmt->execute();

            //Get the nb of rows that we get as result
            $count = $stmt->rowCount();

            //Check if it corresponds
            if($count == 1)
            {
                //Return true if we has the rights
                return true;
            }
            else
            {
                //Return false if not
                return false;
            }
        }
        else
        {
            return false;
        }
    }

    /*
     * Method to check if the user hasn't made any alteration to the value of the input to modify
     * another user's text
     */
    function checkIfRightsToModify($idText)
    {
        //Check if we got the values of session needed
        if(isset($_SESSION['id']))
        {
            //Get the id
            $id = $_SESSION['id'];

            //SQL for the verification
            $sqlVerif = "SELECT texMessage FROM t_text WHERE idText ='$idText' AND fkUser = '$id'";

            //Prepare the request
            $stmt = $this->dbh->prepare($sqlVerif);

            //Execute the request
            $stmt->execute();

            //Get the nb of rows that we get as result
            $count = $stmt->rowCount();

            //Check if it corresponds
            if($count == 1)
            {
                //Return true if we has the rights
                return true;
            }
            else
            {
                //Return false if not
                return false;
            }
        }
        else
        {
            return false;
        }
    }

    /*
     * Method to check if the email is in the database
     * Param @mail used to get the mail
     */
    function checkMail($mail,$useStatus)
    {
        //SQL for the verification
        $sqlVerif = "SELECT idUser FROM t_user WHERE useUsername ='$mail' AND useStatus = '$useStatus'";

        //Prepare the request
        $stmt = $this->dbh->prepare($sqlVerif);

        //Execute the request
        $stmt->execute();

        //Get the nb of rows that we get as result
        $count = $stmt->rowCount();

        //Count if we got a result
        if($count == 1)
        {
            //Return true if the mail exists
            return true;
        }
        else
        {
            //Return false if not
            return false;
        }
    }

    /*
     * Method used to set the token and prepare the mail
     * Param @mail used to get the mail
     */
    function setTokenSendMail($mail)
    {
        //Get a random token
        $token = md5(uniqid(rand()));

        //SQL for the verification
        $sqlUpdateToken = "UPDATE t_user SET useToken = '$token' WHERE  useUsername ='$mail'";

        //Prepare the request
        $stmt = $this->dbh->prepare($sqlUpdateToken);

        //Execute the request
        $stmt->execute();

        //*****GET THE ID OF THE USER FOR UNIQUE KEY
        //SQL for the verification
        $sqlGetId = "SELECT idUser FROM t_user WHERE useUsername ='$mail'";

        //Prepare the request
        $stmt = $this->dbh->prepare($sqlGetId);

        //Execute the request
        $stmt->execute();

        //Get the result
        $result = $stmt->fetchAll();

        //Store the id
        $idUser = $result[0][0];

        //Encode the id
        $key = base64_encode($idUser);
        $id = $key;

        //Body of the mail
        $message = "
          Bonjour,
          <br /><br />
          Bienvenue sur ETML-LEDs,
          pour compléter votre inscription, merci de bien vouloir vous rendre sur le lien ci-dessous:<br/>
          <br /><br />
			<a href='http://www.led.inf.etmlnet.local/src/php/verifyToken.php?id=$id&token=$token'>Cliquez-ici pour activer votre compte</a>
          <br /><br />
          Merci";

        //Body of the mail for unsuported html email providers
        $altMessage = "
                          Bonjour,
                          <br /><br />
                          Bienvenue sur ETML-LEDs,
                          pour completer votre inscription, merci de bien vouloir vous rendre sur le lien ci-dessous:<br/>
                          <br /><br />
                          http://www.led.inf.etmlnet.local/src/php/verifyToken.php?id=$id&token=$token
                          <br /><br />
                          Merci";

        //Subject of the mail
        $subject = "Confirmation de l'inscription ETML-LEDs";

        //Call the method to send the mail
        $this->sendMail($mail,$message,$subject,$altMessage);

    }

    /*
     * Method to send the mail to confirm the acc
     */
    function sendMail($email,$message,$subject,$altMessage)
    {
        //****NE PAS OUBLIER ACTIVER OPENSSL PHP.INI*****

        //Include the necesarry classes
        require_once('mailer/PHPMailerAutoload.php');

        //Instances the class
        $mail = new PHPMailer;

        //$mail->SMTPDebug = 3;                               // Enable verbose debug output

        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = 'mail.educanet2.ch';  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = 'web-info@etml.educanet2.ch';                 // SMTP username
        $mail->Password = 'etmletml';                           // SMTP password
        $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587;                                    // TCP port to connect to

        $mail->setFrom('web-info@etml.educanet2.ch', 'ETML-LEDs');
        $mail->addAddress($email);     // Add a recipient
        $mail->addReplyTo('web-info@etml.educanet2.ch', 'ETML-LEDs');

        $mail->isHTML(true);                                  // Set email format to HTML

        //Set the subject message and alt message
        $mail->Subject = $subject;
        $mail->Body    = $message;
        $mail->AltBody = $altMessage;

        //Check if the mail has been correctly sent
        if(!$mail->send())
        {
            //Redirect the user to an error page
            header("location:displayMessages.php?mailError");
        }
        else
        {
            //Redirect the user to a successfull page
            header("location:displayMessages.php?mailSuccess&email=$email");
        }
    }

    /*
     * Method to confirm an account
     * Param @password used to get the password
     */
    function confirmAccount($password)
    {
        //Store the id and the token
        $idUser = $_SESSION['idToConfirm'];
        $token = $_SESSION['tokenToConfirm'];

        //Unset the sessions
        unset($_SESSION['idToConfirm']);
        unset($_SESSION['tokenToConfirm']);

        //Encrypt the password given
        $passwordEnc=password_hash($password, PASSWORD_BCRYPT, array("cost" => 10));

        //Check if the acc has already been activated
        if($this->accAlreadyActivated($idUser) == 0)
        {
            //Check if the token corresponds with the id of the user
            if($this->checkUserToken($idUser,$token))
            {
                //SQL for the verification
                $sqlUpdatePW = "UPDATE t_user SET usePassword = '$passwordEnc' WHERE idUser ='$idUser'";
                //Prepare the request
                $stmt = $this->dbh->prepare($sqlUpdatePW);
                //Execute the request
                $stmt->execute();

                //SQL for the verification
                $sqlUpdateStatus = "UPDATE t_user SET useStatus = 1 WHERE idUser ='$idUser'";
                //Prepare the request
                $stmt = $this->dbh->prepare($sqlUpdateStatus);
                //Execute the request
                $stmt->execute();

                //Reset the token
                $sqlDelToken = "UPDATE t_user SET useToken='' WHERE idUser = '$idUser'";
                $stmt = $this->dbh->prepare($sqlDelToken);
                $stmt->execute();

                //Redirect the user to the loginpage with a success message
                header("location:loginPage.php?verifSuccess");
            }
            else
            {
                //Redirect the user to an error message page
                header("location:displayMessages.php?tokenInvalid");
            }
        }
        else
        {
            //Redirect the user to the loginpage with a message that the acc is already activated
            header("location:loginPage.php?accAlreadyON");
        }
    }

    /*
     * Method to send the mail for the reset of the password
     */
    function resetPassword($mail)
    {
        //Get a random token
        $token = md5(uniqid(rand()));
        //SQL for the verification
        $sqlUpdateToken = "UPDATE t_user SET useToken = '$token' WHERE  useUsername ='$mail'";
        //Prepare the request
        $stmt = $this->dbh->prepare($sqlUpdateToken);
        //Execute the request
        $stmt->execute();

        $sqlGetId = "SELECT idUser FROM t_user WHERE useUsername ='$mail'";
        //Prepare the request
        $stmt = $this->dbh->prepare($sqlGetId);
        //Execute the request
        $stmt->execute();
        //Get the result
        $result = $stmt->fetchAll();
        //Store the id
        $idUser = $result[0][0];

        //Encode the id
        $key = base64_encode($idUser);
        $id = $key;

        //Body of the mail
        $message = "
          Bonjour,
          <br /><br />
          Veuillez vous rendre sur ce lien pour réinitialiser votre mot de passe:<br/>
          <br /><br />
          <a href='http://www.led.inf.etmlnet.local/src/php/resetPassword.php?id=$id&token=$token'>Cliquez-ici pour reinitaliser votre mot de passe</a>
          <br /><br />
          Merci";

        //Body of the mail for unsuported html email providers
        $altMessage = "
                          Bonjour,
                          <br /><br />
                          Veuillez vous rendre sur ce lien pour reinitialiser votre mot de passe:<br/>
                          <br /><br />
                          http://www.led.inf.etmlnet.local/src/php/resetPassword.php?id=$id&token=$token
                          <br /><br />
                          Merci";

        //Subject of the mail
        $subject = "Reinitialisation de votre mot de passe ETML-LEDs";

        //Call the method to send the mail
        $this->sendMail($mail,$message,$subject,$altMessage);
    }


    /*
     * Method to reset the password of the user in the db
     * Param @password used to get the password
     */
    function resetPasswordInDb($password)
    {
        //Store the id and the token
        $idUser = $_SESSION['idToConfirm'];
        $token = $_SESSION['tokenToConfirm'];

        //Unset the sessions
        unset($_SESSION['idToConfirm']);
        unset($_SESSION['tokenToConfirm']);

        //Encrypt the password given
        $passwordEnc=password_hash($password, PASSWORD_BCRYPT, array("cost" => 10));

        //Check if the acc has already been activated
        if($this->accAlreadyActivated($idUser) == 1)
        {
            //Check if the token corresponds with the id of the user
            if($this->checkUserToken($idUser,$token))
            {
                //SQL for the verification
                $sqlUpdatePW = "UPDATE t_user SET usePassword = '$passwordEnc' WHERE idUser ='$idUser'";
                //Prepare the request
                $stmt = $this->dbh->prepare($sqlUpdatePW);
                //Execute the request
                $stmt->execute();

                //Reset the token
                $sqlDelToken = "UPDATE t_user SET useToken='' WHERE idUser = '$idUser'";
                $stmt = $this->dbh->prepare($sqlDelToken);
                $stmt->execute();

                //Redirect the user to the loginpage with a success message
                header("location:loginPage.php?passwordResOk");
            }
            else
            {
                //Redirect the user to an error message page
                header("location:displayMessages.php?tokenInvalid");
            }
        }
        else
        {
            //Redirect the user to the loginpage with a message that the acc is already activated
            header("location:registerPage.php?notActivated");
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
     * Function to check if the acc has already been activated
     * @idUser gets the id of the user
     */
    function accAlreadyActivated($idUser)
    {
        //Request
        $sqlCompare = "SELECT idUser,useStatus FROM t_user WHERE idUser = '$idUser' LIMIT 1";

        //Prepare
        $stmt = $this->dbh->prepare($sqlCompare);

        //Execute the request
        $stmt->execute();

        //Get the result
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        //Return the value
        return($row['useStatus']);
    }

    /*
     * Function used to verify that the token hasn't been altered
     */
    function checkUserToken($idUser,$token)
    {
        //Request
        $sqlCompare = "SELECT idUser FROM t_user WHERE idUser = '$idUser' AND useToken = '$token' LIMIT 1";

        //Prepare
        $stmt = $this->dbh->prepare($sqlCompare);

        //Execute the request
        $stmt->execute();

        //Get the nb of rows we got
        $count = $stmt->rowCount();

        //Compare the nb of rows
        if($count == 1)
        {
            //Return true if the token hasn't been altered
            return true;
        }
        else
        {
            //Return false if it has been altered
            return false;
        }
    }

    /*
     * Function to return true/false if the user is adin or not
     */
    function isAdmin()
    {
        $id = $_SESSION['id'];

        //Get the nb of activated accs
        $stmt = $this->dbh->prepare("SELECT useIsAdmin FROM t_user WHERE idUser = '$id'");

        //Execute the request
        $stmt->execute();

        //Fetch the result in the array
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if($result[0]['useIsAdmin'] == 1)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
}
?>
                             