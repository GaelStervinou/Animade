window.onload = function(){
    var commentaire = document.querySelectorAll('.commentaire_response');
    for (var i = 0 ; i < commentaire.length; i++) {
        commentaire[i].addEventListener("click", loadCommentaireId, false);
    }
    document.querySelector('#comment_page').addEventListener("click",
        commentPage, false);

    var signaler = document.querySelectorAll('.signaler');
    for (var i = 0 ; i < signaler.length; i++) {
        signaler[i].addEventListener("click", signalerCommentaire, false);
    }

}

function loadCommentaireId(e){
    e.preventDefault();
    var titreFormulaire = document.querySelector('form');
    titreFormulaire.childNodes[1].textContent = 'Répondre à '+e.target.dataset.auteur;
    document.querySelector('#commentaire_id').setAttribute('value', e.target.dataset.id);
    document.querySelector('#comment_page').removeAttribute('hidden');
    window.location.href = '#form';
}

function commentPage(e){
    e.preventDefault();
    var titreFormulaire = document.querySelector('form');
    titreFormulaire.childNodes[1].textContent = 'Répondre à l\'article';
    document.querySelector('#commentaire_id').setAttribute('value', '');
    document.querySelector('#comment_page').setAttribute('hidden', '');
    window.location.href = '#form';
}

function signalerCommentaire(e){
    e.preventDefault();
    var element = $(this);
    $.post("/commentaire/signaler",
        {
            commentaire_id: $(this).data('id'),
        },
        function(data, status){
            if(status === "success") {
                element.after("<p style='color:grey; font-style: italic'>Commentaire signalé</p>")
                element.remove();
            }else{
                alert('tst');
            }
            alert( data );
        })
}
