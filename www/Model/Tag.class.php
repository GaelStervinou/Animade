<?php

namespace App\Model;

use App\Core\BaseSQL;

class Tag extends BaseSQL{
    /** @var int|null $id */
    private $id = null;

    /** @var string|null $nom */
    protected $nom = null;

    /** @var int|null $statut */
    protected $statut = null;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
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

    public function delete()
    {
        $this->setStatut(-1);
        $this->save();
    }
}