function rollOver(showHide, link, menu)
{
    var linkObj = document.getElementById(link);
    var menuObj = document.getElementById(menu);
    var isVisible = showHide != 0;

    menuObj.style.display = isVisible ? 'block' : 'none';
    linkObj.className = isVisible ? 'selectedRollover' : 'mainNav';
}