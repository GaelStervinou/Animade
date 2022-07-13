$(document).ready(function(){

    var personnageId = getUrlParameter('personnage_id');
    if(personnageId === false){
        var personnageNom = getUrlParameter('personnage');
    }

    if(personnageId !== false){
        $.get("/page/pages?personnage_id="+personnageId, function(data){
            $('#list_pages').html(data);
        });

        const nomPersonnage = $('h1').text();
        const nextURL = '/personnage?personnage='+encodeURIComponent(nomPersonnage);
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