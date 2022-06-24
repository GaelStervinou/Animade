<?php

namespace App\Model;

use App\Core\BaseSQL;

class Categorie extends BaseSQL{
    /** @var int|null $id */
    private $id = null;

    /** @var Categorie $parent_id */
    protected $parent_id = null;

    /** @var string|null $nom */
    protected $nom = null;

    /** @var string|null $description */
    protected $description = null;

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
     * @return Categorie
     */
    public function getParentId(): ?Categorie
    {
        return $this->parent_id;
    }

    /**
     * @param Categorie $parent_id
     */
    public function setParentId(?Categorie $parent_id): void
    {
        $this->parent_id = $parent_id;
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

    public function save()
    {
        parent::save();
    }

    public function __construct()
    {
        parent::__construct();
    }

    public function delete()
    {
        $this->setStatut(-1);
        $this->save();
    }
}
