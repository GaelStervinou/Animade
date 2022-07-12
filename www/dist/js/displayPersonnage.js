$(document).ready(function(){

    var personnageId = getUrlParameter('personnage_id');
    if(personnageId === false){
        personnageNom = getUrlParameter('personnage');
    }



    if(personnageId !== false){
        $.get("/page/pages?personnage_id="+personnageId, function(data){
            $('#list_pages').html(data);
        });

        const nomPersonnage = +$('h1').text();
        const nextURL = '/personnage?personnage='+nomPersonnage;
        const nextTitle = nomPersonnage;
        const nextState = { additionalInformation: 'Updated the URL with JS' };

        window.history.pushState(nextState, nextTitle, nextURL);

        window.history.replaceState(nextState, nextTitle, nextURL);
    }else{
        $.get("/page/pages?personnage="+personnageNom, function(data){
            $('#list_pages').html(data);
        });
    }

});

function getUrlParameter(sParam){
    var sPageURL = window.location.search.substring(1),
        sURLVariables = sPageURL.split('&'),
        sParameterName,
        i;

    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');

        if (sParameterName[0] === sParam) {
            return sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
        }
    }
    return false;
};