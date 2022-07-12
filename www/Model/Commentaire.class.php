<?php

namespace App\Model;

use App\Core\BaseSQL;
use App\Core\Security;
use App\Model\Page as PageModel;
use App\Model\User as UserModel;
use App\Model\Media as MediaModel;

class Commentaire extends BaseSQL{
    /** @var int|null $id */
    private $id = null;

    /** @var int|null $auteur_id */
    protected $auteur_id = null;

    /** @var UserModel $auteur */
    protected $auteur = null;

    /** @var int|null $commentaire_id */
    protected $commentaire_id = null;

    /** @var Commentaire $commentaire */
    protected $commentaire = null;

    /** @var int|null $page_id */
    protected $page_id = null;

    /** @var PageModel $page */
    protected $page = null;

    /** @var int|null $media_id */
    protected $media_id = null;

    /** @var MediaModel $media */
    protected $media = null;

    /** @var string|null $contenu */
    protected $contenu = null;

    /** @var int|null $statut */
    protected $statut = null;

    /** @var $date_creation */
    protected $date_creation = null;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return int|null
     */
    public function getAuteurId(): ?int
    {
        return $this->auteur_id;
    }

    /**
     * @param int|null $auteur_id
     */
    public function setAuteurId(?int $auteur_id): void
    {
        $this->auteur_id = $auteur_id;
    }

    /**
     * @return UserModel|null
     */
    public function getAuteur(): ?UserModel
    {
        if(!empty($this->getAuteurId())){
            $user = new UserModel();
            return $user->setId($this->getAuteurId());
        }else{
            return null;
        }
    }

    /**
     * @param UserModel|null $auteur
     */
    public function setAuteur(?UserModel $auteur): void
    {
        $this->auteur = $auteur;
    }

    /**
     * @return int|null
     */
    public function getCommentaireId(): ?int
    {
        return $this->commentaire_id;
    }

    /**
     * @param int|null $commentaire_id
     */
    public function setCommentaireId(?int $commentaire_id): void
    {
        $this->commentaire_id = $commentaire_id;
    }

    /**
     * @return Commentaire|null
     */
    public function getCommentaire(): ?Commentaire
    {
        if(!empty($this->hasCommentaire())){
            $commentaire = new Commentaire();
            return $commentaire->setId($this->getCommentaireId());
        }

        return null;
    }

    public function getCommentaires()
    {
        return $this->hasMany(Commentaire::class);
    }

    public function hasCommentaire()
    {
        return !empty($this->getCommentaireId());
    }

    /**
     * @param Commentaire|null $commentaire
     */
    public function setCommentaire(?Commentaire $commentaire): void
    {
        $this->commentaire = $commentaire;
    }

    /**
     * @return int|null
     */
    public function getPageId(): ?int
    {
        return $this->page_id;
    }

    /**
     * @param int|null $page_id
     */
    public function setPageId(?int $page_id): void
    {
        $this->page_id = $page_id;
    }

    /**
     * @return Page
     */
    public function getPage(): ?Page
    {
        return $this->page;
    }

    /**
     * @param PageModel|null $page
     */
    public function setPage(?Page $page): void
    {
        $this->page = $page;
    }

    /**
     * @return int|null
     */
    public function getMediaId(): ?int
    {
        return $this->media_id;
    }

    /**
     * @param int|null $media_id
     */
    public function setMediaId(?int $media_id): void
    {
        $this->media_id = $media_id;
    }

    /**
     * @return MediaModel
     */
    public function getMedia(): ?MediaModel
    {
        if(!empty($this->hasMedia())){
            $media = new MediaModel();
            return $media->setId($this->getMediaId());
        }else{
            return false;
        }
    }

    public function hasMedia()
    {
        return !empty($this->getMediaId());
    }

    /**
     * @param MediaModel|null $media
     */
    public function setMedia(?MediaModel $media): void
    {
        $this->media = $media;
    }

    /**
     * @return string|null
     */
    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    /**
     * @param string|null $contenu
     */
    public function setContenu(?string $contenu): void
    {
        $this->contenu = $contenu;
    }

    /**
     * @return int|null
     */
    public function getStatut(): ?int
    {
        return $this->statut;
    }

    /**
     * @param int|null $statut
     */
    public function setStatut(?int $statut): void
    {
        $this->statut = $statut;
    }

    /**
     * @return mixed
     */
    public function getDateCreation()
    {
        return $this->date_creation;
    }

    public function __construct()
    {
        parent::__construct();
    }

    public function save()
    {
        parent::save();
    }

    public function delete()
    {
        parent::delete();
    }

    public function getLastCommentaire()
    {
        $listCommentaires = [];

        if(!empty($this->getCommentaires())){
            /*foreach ($this->getCommentaires() as $commentaire){
                $listCommentaires[$commentaire->getId()] = $commentaire->getLastCommentaire();
            }*/

        }
        return $listCommentaires;
    }

    public function isSignaledByCurrentUser()
    {
        $signalement = new Signalement();
        if($signalement->findOneBy($signalement->getTable(), ['commentaire_id' => $this->getId(), 'user_id' => Security::getUser()->getid()])) {
            return true;
        }
        return false;
    }

    public function getFormNewCommentaire(): array
    {
        return [
            'config' => [
                'method' => 'POST',
                'action' => '/commentaire/new',
                'submit' => "Commenter",
                'title' => "Nouveau commentaire",
            ],
            'inputs' => [
                'media_name' => [
                    'type' => 'text',
                    'label' => 'Nom image :',
                    'placeholder' => 'Nom image',
                    'id' => 'nomMediaNewCommentaire',
                    'class' => 'inputRegister',
                    'error' => 'nom incorrect',
                ],
                'media' => [
                    'type' => 'file',
                    'label' => 'Image :',
                    'id' => 'mediaNewCommentaire',
                    'class' => 'inputRegister',
                    'error' => 'Image incorrecte',
                ],
                'contenu' => [
                    'type' => 'wysiwyg',
                    'label' => 'Contenu :',
                    'placeholder' => 'Vous pouvez rédiger votre commentaire ici.',
                    'id' => 'contenuNewCommentaire',
                    'required' => true,
                    'error' => 'Contenu incorrect',
                    'default_value' => '',
                ],
            ],
        ];
    }
}