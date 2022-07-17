$(document).ready(function(){

    var xhr = new XMLHttpRequest();
    var urlToFile = "dist/main.css";
    xhr.open('HEAD', urlToFile, false);
    xhr.send();

    if (xhr.status == "404") {
        $('head').append('<link rel="stylesheet" href="../dist/main.css" type="text/css" />');
    }else{
        $('head').append('<link rel="stylesheet" href="dist/main.css" type="text/css" />');
    }

});