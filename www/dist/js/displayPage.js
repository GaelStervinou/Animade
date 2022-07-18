window.onload = function(){
    var commentaire = document.querySelectorAll('.commentaire_response');
    for (var i = 0 ; i < commentaire.length; i++) {
        commentaire[i].addEventListener("click", loadCommentaireId, false);
    }
    document.querySelector('#comment_page').addEventListener("click",
        commentPage, false);

    $('.like_action').on('click', function(e){
        element = $(this);
        $.get("/like?page_id="+element.data('page_id')+"&like="+element.data('like'), function (data){

            if(element.data('like') === 1){
                var color = 'green';
                $('#unlike').removeAttr('style').css('cursor', 'pointer');

            }else{
                var color = 'red';
                $('#like').removeAttr('style').css('cursor', 'pointer');
            }
            if(data === "reset like"){
                element.removeAttr('style').css('cursor', 'pointer');
            }else{
                element.css('border-style', 'dotted').css('border-color',color).css('cursor', 'pointer');
            }

            getCountLikes(element.data('page_id'))
            getCountUnlikes(element.data('page_id'))
        });
    });
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

function getCountLikes(page_id){
    $.get("/page/countLikes?page_id="+page_id, function(data){
        alert(data);
        $('#likesCount').text(data);
    });
}

function getCountUnlikes(page_id){
    $.get("/page/countUnlikes?page_id="+page_id, function(data){
        $('#unlikesCount').text(data);
    });
}
