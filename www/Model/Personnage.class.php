<?php

namespace App\Model;

use App\Core\BaseSQL;
use App\Model\Media as MediaModel;

class Personnage extends BaseSQL{
    /** @var int|null $id */
    private $id = null;

    /** @var string|null $nom */
    private $nom = null;

    /** @var int|null $media_id */
    private $media_id = null;

    /** @var MediaModel|null $media */
    private $media = null;

    /** @var int|null $statut */
    protected $statut = null;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param int|null $id
     */
    public function setId(?int $id): void
    {
        $this->id = $id;
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
     * @return MediaModel|null
     */
    public function getMedia(): ?MediaModel
    {
        return $this->media;
    }

    /**
     * @param MediaModel|null $media
     */
    public function setMedia(?MediaModel $media): void
    {
        $this->media = $media;
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