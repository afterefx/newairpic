var status=0;
function toggleLogin()
{

    var menu = document.getElementById('accountMenu');
    if (status == 1)
    {
        menu.style.visibility = 'hidden';
        status = status - 1;
    }
    else
    {
        menu.style.visibility = 'visible';
        status = status + 1;
    }
}
