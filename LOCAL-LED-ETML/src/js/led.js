/**
 * Created by carvalhoda on 31.05.2016.
 */

//Function to check the form of the new message when the send button is pressed
function checkNewMessageForm(form)
{
    //Get the select for the speedlist
    var e = document.getElementById("speedList");

    //Get the figure select
    var b = document.getElementById("figList");

    //Check the selected option in the select because it has to be between 1 and 6 seconds.
    if(e.options[e.selectedIndex].value < 1 || e.options[e.selectedIndex].value > 5)
    {
        //Show an alert if the text is too big
        alert("Merci de sélectionner une vitesse entre 1 et 5 secondes !");

        //Return false to not post
        return false;
    }
    else
    {
        //Check if the selected option hasn't been altered
        if(b.options[b.selectedIndex].value < 1 || b.options[b.selectedIndex].value > 2)
        {
            //Show an alert if the text is too big
            alert("Merci de sélectionner une figure valide !");

            return true;
        }
        else
        {
            return true;
        }
    }
}

//Method to check the length of the text
function checkTextLength(form)
{
    //Get the input
    txtMessageBox = document.getElementById("textMsg");

    //Check the length of the text
    if(txtMessageBox.value.length > 55)
    {
        //Show an alert if the text is too big
        alert("Vous ne pouvez pas afficher une phrase de plus de 55 charactères !");
        return false;
    }
    else
    {
        return true;
    }
}

//Method to check the length of the text that has been modified
function checkTextLengthMod(form)
{
    //Loop to check all the input textbox
    for (var i=0;i<document.getElementsByName('messageInput').length;i++)
    {
        if(document.getElementsByName('messageInput')[i].disabled == false)
        {

            //Check the length of the text
            if(document.getElementsByName('messageInput')[i].value.length > 55)
            {
                //Show an alert if the text is too big
                alert("Vous ne pouvez pas afficher une phrase de plus de 55 charactères !");
                return false;
            }
            else
            {
                if(document.getElementsByName('messageInput')[i].value.length < 1)
                {
                    //Show an alert if the text is too big
                    alert("Vous ne pouvez pas afficher une phrase sans caractères !");
                    return false
                }
                else
                {
                    return true;
                }
            }
        }
    }
}

previousValue = "";

//Method when a modify button is pressed
function unlockInput(button)
{
    //Loop to check all the input textbox
    for (var i=0;i<document.getElementsByName('messageInput').length;i++)
    {
        //******DISABLE ALL THE INPUTS THAT DOESN'T CORRESPONDS*****//
        //Method to check if the input doesn't corresponds to the pressed button id
        if(document.getElementsByName('messageInput')[i].id != button.value)
        {
            //Disable the input
            document.getElementsByName('messageInput')[i].disabled = true;

            //Set the readonly parameter
            document.getElementsByName('messageInput')[i].readOnly = true;
        }
        else
        {
            //********ENABLE THE CORRESPONDING INPUT AND DISABLE ALL THE OTHER BUTTONS//
            //Check if the input corresponds to the id of the button and that it's disabled
            if ((document.getElementsByName('messageInput')[i].id == button.value) && (document.getElementsByName('messageInput')[i].disabled == true))
            {
                //Enable the input
                document.getElementsByName('messageInput')[i].disabled = false;

                //Unset the readonly parameter
                document.getElementsByName('messageInput')[i].readOnly = false;

                //Save the actual value to a variable
                previousValue = document.getElementsByName('messageInput')[i].value;

                //Call the disable button function
                disableButtons();

                //Re-enable only the button used
                button.disabled = false;

                //Get the hidden input and set the id of the input that it's being modified by the user
                document.getElementById('hiddenID').value = document.getElementsByName('messageInput')[i].id;
            }
            else
            {
                //*********SET THE PREVIOUS VALUE, DISABLE THE CORRESPONDING INPUT AND ENABLE ALL THE BUTTONS//
                //Check if the input corresonds to the button id and that's enabled
                if((document.getElementsByName('messageInput')[i].id == button.value) && (document.getElementsByName('messageInput')[i].disabled == false))
                {
                    //Set the previous value to the input
                    document.getElementsByName('messageInput')[i].value = previousValue;

                    //Disable the input
                    document.getElementsByName('messageInput')[i].disabled = true;

                    //Set the readOnly parameter
                    document.getElementsByName('messageInput')[i].readOnly = true;

                    //Enable all the buttons
                    enableButtons();

                    //Get the hidden input and set the id of the input that it's being modified to null-> ""
                    document.getElementById('hiddenID').value = "NULL";

                }
            }
        }
    }
}

//Method to enable all the modify buttons
function enableButtons()
{
    //Loop all the buttons
    for (var i=0;i<document.getElementsByName('modButton').length;i++)
    {
        //Enable each button
        document.getElementsByName('modButton')[i].disabled = false;
    }
}

//Method to disable all the modify buttons
function disableButtons()
{
    //Loop all the buttons
    for (var i=0;i<document.getElementsByName('modButton').length;i++)
    {
        //Disable each button
        document.getElementsByName('modButton')[i].disabled = true;
    }
}

