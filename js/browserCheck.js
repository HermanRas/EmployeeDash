var ua = window.navigator.userAgent;
var msie = ua.indexOf("rv:11.0");
var msieOld = ua.indexOf("MSIE");

if ((msie > 0) || (msieOld > 0)) // If Internet Explorer, return version number
{
    let response = '<h1>Please use a modern browser</h1><br><img alt="Please update your browser" width="650px;" src="img/ModernBrowser.png" border="0">'
    document.getElementById("Loader").innerHTML = response;
}
