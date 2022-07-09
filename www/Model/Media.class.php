<?php

namespace App\Model;

use App\Core\BaseSQL;
use App\Model\User as UserModel;

class Media extends BaseSQL{

    /** @var int|null $id */
    private $id = null;

    /** @var int|null $user_id */
    protected $user_id = null;

    /** @var UserModel $user */
    protected $user = null;

    /** @var string|null $nom */
    protected $nom = null;

    /** @var string|null $description */
    protected $description = null;

    /** @var string|null $chemin */
    protected $chemin = null;

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
     * @return User
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser(?User $user): void
    {
        $this->user = $user;
    }

    /**
     * @return string|null
     */
    public function getNom(): ?string
    {
        return $this->nom;
    }

    /**
     * @param string|null $nom
     */
    public function setNom(?string $nom): void
    {
        $this->nom = $nom;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     */
    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return string|null
     */
    public function getChemin(): ?string
    {
        return $this->chemin;
    }

    /**
     * @param string|null $chemin
     */
    public function setChemin(?string $chemin): void
    {
        $this->chemin = $chemin;
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

    public function __construct()
    {
        parent::__construct();
    }

    public function save()
    {
        return parent::save();
    }

    public function delete()
    {
        $this->setStatut(-1);
        $this->save();
    }
}