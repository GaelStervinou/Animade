$(document).ready(function(){

    var signaler = document.querySelectorAll('.signaler');
    for (var i = 0 ; i < signaler.length; i++) {
        signaler[i].addEventListener("click", signalerCommentaire, false);
    }

});

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
                alert('Erreur lors de la signalation');
            }
        })
}