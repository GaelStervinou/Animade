$(document).ready(function(){
    var chapitreId = getUrlParameter('chapitre_id');
    if(chapitreId === false){
        var chapitreNom = getUrlParameter('chapitre');
    }

    if(chapitreId !== false){
        $.get("/page/pages?chapitre_id="+chapitreId, function(data){
            $('#list_pages').html($(data).find("#table_id"));
            updateDataTable();
        });

        const nomChapitre = $('#nom_chapitre').text();
        const nextURL = '/chapitre?chapitre='+encodeURIComponent(nomChapitre);
        const nextTitle = nomChapitre;
        const nextState = { additionalInformation: 'Updated the URL with JS' };

        window.history.pushState(nextState, nextTitle, nextURL);

        window.history.replaceState(nextState, nextTitle, nextURL);
    }else{
        $.get("/page/pages?chapitre="+chapitreNom, function(data){
            $('#list_pages').html($(data).find("#table_id"));
            updateDataTable();
        });
    }
});

