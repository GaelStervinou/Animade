<main>
            <section id="login-form">
                <div class="grid">
                    <div id="login-form">
                    <div class="table">
                       
    <h1>

        <?= $page->getTitre(); ?></h1>
    <h2><?= $page->getDescription(); ?></h2>

    

    <h3>

    </h3>

    <div>
        <?= $page->getContenu();?>
    </div>
    <?php
    if($page->hasMedia()):
        ?>
        <div>

            <img src="../<?= $page->getMedia()->getChemin()?>" alt="Image de l'article"
                 width="100"
                 height="150" >
        </div>
    <?php
    endif; ?>
    <br><br>
    <div>
        By : <?= $page->getAuteur()->getFullName();
        ?>
    </div>

    <div>
        <?= \App\Helpers\Formalize::formalizeDateYearMonthDay($page->getDateCreation())?>
    </div>
    <?php
    if($can_edit == "yes"):
        ?>
        <h4>
            <a href="/page/update?page_id=<?= $page->getId()?>">Modifier la page</a>
        </h4>
    <?php
    endif;
    ?>
    <div>
        <?php
        if($can_comment === "yes"):
            ?>
            <br>
            <span class="like_action" id="like"
              <?php
              if($user_like !== false && $user_like->getAime() === 1):
                  ?>
                  style="border: dotted green; cursor: pointer;"
              <?php
              else:
                  ?>
                  style="cursor: pointer;"
              <?php
              endif;
              ?>
              data-page_id="<?= $page->getId()?>" data-like="1">
            <i class="fa-solid fa-thumbs-up fa-lg"></i>
        </span>
            <span style="margin-right: 30px; margin-left: 5px;" id="likesCount">
                <?= $page->countLikes()?>
            </span>
            <span class="like_action" id="unlike"
                  <?php
                  if($user_like !== false && $user_like->getAime() === -1):
                      ?>
                      style="border: dotted green; cursor: pointer;"
                  <?php
                  else:
                  ?>
    style="cursor: pointer;"
                  <?php
                  endif;
                  ?>data-page_id="<?= $page->getId()?>" data-like="-1">
            <i class="fa-solid fa-thumbs-down fa-lg"></i>
         </span>
            <span style="margin-right: 30px; margin-left: 5px;" id="unlikesCount">
                <?= $page->countUnlikes()?>
            </span>

        <?php
        endif;
        ?>
    </div>

    <div>
        <br>
        <br>
        <br>
        <br>
        
        Commentaires :
        <?php
        $commentaireList = $page->getCommentaires();
        foreach($commentaireList as $commentaire):
            $replies = [];
            if(empty($commentaire->getCommentaireId())){
                foreach($commentaireList as $reply_key => $reply){
                    if($reply->getCommentaireId() == $commentaire->getId()){
                        $replies[] = $reply;
                        unset($commentaireList[$reply_key]);
                    }
                }
            }
            $commentaire->replies = $replies;
        endforeach;
        foreach ($commentaireList as $commentaire):
            if($commentaire->getStatut() === 2):
                ?>
                <div style="border: dashed red;">
                    <?=$commentaire->getContenu()?>
                    <?=$commentaire->getAuteur()->getFullName()?>
                    <?php
                    if($commentaire->hasMedia()):
                        ?>
                        <img src="../<?= $commentaire->getMedia()->getChemin()?>" alt="Image de l'article"
                             width="100"
                             height="150" >
                    <?php
                    endif;
                    if($user->getRoleId() == 1 && empty($commentaire->getCommentaireId()) && $user->getId() !== $commentaire->getAuteurId()):
                        ?>
                        <a href="#" class="commentaire_response" data-auteur="<?=$commentaire->getAuteur()->getFullName()?>" data-id="<?=$commentaire->getId()?>">Répondre</a>
                    <?php
                    endif;
                    if($commentaire->isSignaledByCurrentUser() === false && $user->getId() !== $commentaire->getAuteurId()):
                        ?>
                        <a href="#" data-id="<?=$commentaire->getId()?>" class="signaler" style="color:red">Signaler le commentaire</a>
                    <?php
                    elseif($user->getId() !== $commentaire->getAuteurId()):
                        ?>
                        <p style="color:grey; font-style: italic">Commentaire signalé</p>
                    <?php
                    else:
                        ?>
                        <a href="commentaire/delete?commentaire_id=<?= $commentaire->getId()?>">Supprimer</a>
                    <?php
                    endif;
                    ?>
                </div>
            <?php
            else:
                ?>
                <div style="border: dashed red;">
                    <p style="color:grey; font-style: italic">Commentaire supprimé</p>
                </div>
            <?php
            endif;
            foreach ($commentaire->replies as $reply):
                if($reply->getStatut() === 2):
                    ?>
                    <div style="border: dashed black; margin-left: 30px;margin-top:10px;margin-bottom:10px;">
                        <?=$reply->getContenu()?>
                        <?=$reply->getAuteur()->getFullName()?>
                        <?php
                        if($reply->hasMedia()):
                            ?>
                            <img src="../<?= $reply->getMedia()->getChemin()?>" alt="Image de l'article"
                                 width="100"
                                 height="150" >
                        <?php
                        endif;
                        if($reply->isSignaledByCurrentUser() === false && $user->getId() !== $reply->getAuteurId()):
                            ?>
                            <a href="#" data-id="<?=$reply->getId()?>" class="signaler" style="color:red">Signaler le commentaire</a>
                        <?php
                        elseif($user->getId() !== $reply->getAuteurId()):
                            ?>
                            <p style="color:grey; font-style: italic">Commentaire signalé</p>
                        <?php
                        else:
                            ?>
                            <a href="commentaire/delete?commentaire_id=<?= $reply->getId()?>">Supprimer</a>
                        <?php
                        endif;
                        ?>
                    </div>
                <?php
                else:
                    ?>
                    <div style="border: dashed black; margin-left: 30px;margin-top:10px;margin-bottom:10px;">
                        <p style="color:grey; font-style: italic">Commentaire supprimé</p>
                    </div>
                <?php
                endif;
            endforeach;

        endforeach;
        ?>
        <br>
        <br>
        <br>
        <br>
    </div>
    <div id="commentaire">
        <?php
        if($can_comment === "yes"):
            ?>
            <a href="#" hidden id="comment_page">Répondre à l'article</a>
            <?php
            $this->includePartial('form', $commentaire->getFormNewCommentaire());
        endif;
        ?>
        <input type="hidden" form="form" name="page_id" value="<?=$page->getId()?>">
        <input type="hidden" form="form" id="commentaire_id" name="commentaire_id" value="">
    </div>
    </div>
                    </div>
                </div>
            </section>
        </main>