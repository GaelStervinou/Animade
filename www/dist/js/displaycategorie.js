$(document).ready(function(){
    var categorieId = getUrlParameter('categorie_id');
    if(categorieId === false){
        var categorieNom = getUrlParameter('categorie');
    }

    if(categorieId !== false){
        $.get("/page/pages?categorie_id="+categorieId, function(data){
            $('#list_pages').html($(data).find("#table_id"));
            updateDataTable();
        });

        const nomCategorie = $('#nom_categorie').text();
        const nextURL = '/categorie?categorie='+encodeURIComponent(nomCategorie);
        const nextTitle = nomCategorie;
        const nextState = { additionalInformation: 'Updated the URL with JS' };

        window.history.pushState(nextState, nextTitle, nextURL);

        window.history.replaceState(nextState, nextTitle, nextURL);
    }else{
        $.get("/page/pages?categorie="+categorieNom, function(data){
            $('#list_pages').html($(data).find("#table_id"));
            updateDataTable();
        });
    }
});