var status=0;
function toggleLogin()
{
    if (status == 1)
    {
        document.getElementById('loginMenu').style.visibility = 'hidden';
        document.getElementById('blankScreen').style.visibility = 'hidden';
        status = status - 1;
    }
    else
    {
        document.getElementById('loginMenu').style.visibility = 'visible';
        document.getElementById('blankScreen').style.visibility = 'visible';
        status = status + 1;
    }
}
