<?php

namespace App\Model;

use App\Model\User as UserModel;
use App\Model\Commentaire as CommentaireModel;
use App\Core\BaseSQL;

class Signalement extends BaseSQL{
    /** @var int|null $id */
    private $id = null;

    /** @var int|null $user_id */
    protected $user_id = null;

    /** @var int|null $commentaire_id */
    protected $commentaire_id = null;

    /** @var $date_creation */
    protected $date_creation = null;

    /** @var int|null $statut */
    protected $statut = null;


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
    public function getUserId(): ?int
    {
        return $this->user_id;
    }

    /**
     * @param int|null $user_id
     */
    public function setUserId(?int $user_id): void
    {
        $this->user_id = $user_id;
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

    public function getCommentaire()
    {
        $commentaire = new CommentaireModel();
        return $commentaire->setId($this->getCommentaireId());
    }

    public function getUser()
    {
        $user = new UserModel();
        return $user->setId($this->getUserId());
    }


    public function save()
    {
        parent::save();
    }

    public function delete()
    {
        $this->setStatut(-1);
        $this->save();
    }

    public function __construct()
    {
        parent::__construct();
    }


}