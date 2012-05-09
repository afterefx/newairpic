//variables used to keep status of valid information in a field
var pass = true;
var first = true;
var last = true;
var zip = true;
var email = true;
var eMatch = true;

//show loading animation
function loading(username)
{ document.getElementById("availability").innerHTML= "<img src=\"images/load.gif\" />"; }

//ajax call to see if username is avaiable
function checkForUsername(str)
{//{{{2
    if (str=="")//initialize the inner HTML to nothing
    {
        document.getElementById("availability").innerHTML="";
        return;
    }

    //if an xmlhttp request is called from the window create one
    if (window.XMLHttpRequest)
    {// code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp=new XMLHttpRequest();
    }
    else //or make one for ie6 and ie5
    {// code for IE6, IE5
        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    }

    //when a result is received put it into the inner HTML of the
    //availability id
    xmlhttp.onreadystatechange=function()
    {
        if (xmlhttp.readyState==4 && xmlhttp.status==200)
        {
            document.getElementById("availability").innerHTML=xmlhttp.responseText;
        }
    }
    //create a post request
    xmlhttp.open("POST","requestHandler/checkUsername.php",true);
    //make the headers and say that we are going to submit a form
    xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
    //send our arguments
    xmlhttp.send("q="+str);
}//}}}2

//validate that the passwords in the password field and confirm password
//field match
function checkPassMatch()
{//{{{2

    //if the password field and the confirm password field are empty then
    //the inner html needs should be blank
    if((document.getElementById("password").value=="" ||
                document.getElementById("password").value==null) &&
            (document.getElementById("confirmPass").value=="" || document.getElementById("confirmPass").value==null))
        document.getElementById("passMatch").innerHTML="";

    //if the strings match each other, then send output that they do
    else if(document.getElementById("password").value==document.getElementById("confirmPass").value)
    {
        document.getElementById("passMatch").innerHTML="<img src=\"images/check.png\" /> <i>Passwords match</i>";
        pass=true;
        enableSubmit();
    }

    //else show the user that they do not match
    else
    {
        document.getElementById("passMatch").innerHTML="<img src=\"images/x.png\" /> <b>Passwords do not match</b>";
        pass=false;
        disableSubmit();
    }

}//}}}2

//validate that the firstname falls within the speicifed regex
function checkFirstName()
{//{{{2

    //regular expression to test against
    var regTest = /^[a-zA-Z']([A-Za-z'\-]*[A-Za-z]+$)|((\s[A-Za-z][A-Za-z'\-]*[A-Za-z])+$)/;
    //check to see if the strings are empty
    if((document.getElementById("firstname").value=="" || document.getElementById("firstname").value==null))
        document.getElementById("validFirst").innerHTML="";//make a blank string to show the user

    //if the regular expression test succeeds show a checkmark
    else if(regTest.test(document.getElementById("firstname").value))
    {
        document.getElementById("validFirst").innerHTML="<img src=\"images/check.png\" />";
        first=true;
        enableSubmit();
    }

    //if the regular expression test does not succeed show an X
    else
    {
        document.getElementById("validFirst").innerHTML="<img src=\"images/x.png\" /> <b>Invalid characters</b>";
        first=false;
        disableSubmit();
    }

}//}}}2

//validate that the last name falls within the speicifed regex
function checkLastName()
{//{{{2
    //regular expression to test against
    var regTest = /^[a-zA-Z']([A-Za-z'\-]*[A-Za-z]+$)|((\s[A-Za-z][A-Za-z'\-]*[A-Za-z])+$)/;
    //check to see if the strings are empty
    if((document.getElementById("lastname").value=="" || document.getElementById("lastname").value==null))
        document.getElementById("validLast").innerHTML="";

    //if the regular expression test succeeds show a checkmark
    else if(regTest.test(document.getElementById("lastname").value))
    {
        document.getElementById("validLast").innerHTML="<img src=\"images/check.png\" />";
        last=true;
        enableSubmit();
    }

    //if the regular expression test does not succeed show an X
    else
    {
        document.getElementById("validLast").innerHTML="<img src=\"images/x.png\" /> <b>Invalid characters</b>";
        last=false;
        disableSubmit();
    }

}//}}}2

//validate that the zipcode falls within the regex
function checkEmail()
{//{{{2
    //regex to test against
    var regTest = /^[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?$/;
    //check to see if the strings are empty
    if((document.getElementById("email").value=="" || document.getElementById("email").value==null))
        document.getElementById("validEmail").innerHTML="";

    //if the regular expression test succeeds show a checkmark
    else if(regTest.test(document.getElementById("email").value))
    {
        document.getElementById("validEmail").innerHTML="<img src=\"images/check.png\" />";
        email=true;
        enableSubmit();
    }

    //if the regular expression test does not succeed show an X
    else
    {
        document.getElementById("validEmail").innerHTML="<img src=\"images/x.png\" /> <b>Invalid email</b>";
        email=false;
        disableSubmit();
    }
}//}}}2

//validate that the emails in the email field and confirm email
//field match
function checkEmailMatch()
{//{{{2
    //if the email field and the confirm email field are empty then
    //the inner html needs should be blank
    if((document.getElementById("email").value=="" || document.getElementById("email").value==null) &&
            (document.getElementById("confirmEmail").value=="" || document.getElementById("confirmEmail").value==null))
        document.getElementById("emailMatch").innerHTML="";

    //if the strings match each other, then send output that they do
    else if(document.getElementById("email").value==document.getElementById("confirmEmail").value)
    {
        document.getElementById("emailMatch").innerHTML="<img src=\"images/check.png\" /> <i>Emails match</i>";
        eMatch=true;
        enableSubmit();
    }

    //else show the user that they do not match
    else
    {
        document.getElementById("emailMatch").innerHTML="<img src=\"images/x.png\" /> <b>Emails do not match</b>";
        eMatch=false;
        disableSubmit();
    }

}//}}}2

//enables the submit button
function enableSubmit()
{//{{{2
    if(pass && first && last && zip && email && eMatch)
        document.getElementById("submitButton").disabled=false;
}//}}}2

//disables the submit button
function disableSubmit()
{//{{{2
    if(!pass || !first || !last || !zip || !email || !eMatch)
        document.getElementById("submitButton").disabled=true;
}//}}}2

