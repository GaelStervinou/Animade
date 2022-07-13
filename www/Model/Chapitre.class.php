<?php

namespace App\Model;

use App\Core\BaseSQL;
use App\Model\Media as MediaModel;

class Chapitre extends BaseSQL{

    /** @var int|null $id */
    private $id = null;

    /** @var string|null $titre */
    protected $titre = null;

    /** @var int|null $media_id */
    protected $media_id = null;

    /** @var MediaModel $media */
    protected $media = null;

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
    public function getTitre(): ?string
    {
        return $this->titre;
    }

    /**
     * @param string|null $titre
     */
    public function setTitre(?string $titre): void
    {
        $this->titre = $titre;
    }

    /**
     * @return int|null
     */
    public function getMediaId(): ?int
    {
        return $this->media_id;
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
     * @param int|null $media_id
     */
    public function setMediaId(?int $media_id): void
    {
        $this->media_id = $media_id;
    }

    /**
     * @param Media $media
     */
    public function setMedia(?Media $media): void
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
        return parent::save();
    }

    public function delete()
    {
        parent::save();
    }

    public function getChapitreFromTitre(string $titre): ?Chapitre
    {
        return $this->findOneBy($this->getTable(), ['titre' => $titre]);
    }

    public function toString(): string
    {
        return $this->getTitre();
    }

    public function getNewChapitreForm()
    {
        return [
            'config' => [
                'method' => 'POST',
                'action' => '',
                'submit' => "Nouveau chapitre",
                'title' => "Nouveau chapitre",
            ],
            'inputs' => [
                'titre' => [
                    'type' => 'text',
                    'label' => 'Titre :',
                    'placeholder' => 'Nom',
                    'id' => 'titreNewChapitre',
                    'class' => 'inputRegister',
                    'required' => true,
                    'min' => 2,
                    'max' => 75,
                    'error' => "Le titre est incorrect",
                ],
                'media_name' => [
                    'type' => 'text',
                    'label' => 'Nom image :',
                    'placeholder' => 'Nom image',
                    'id' => 'nomMediaNewChapitre',
                    'class' => 'inputRegister',
                    'error' => 'nom incorrect',
                    'required' => false,
                ],
                'media' => [
                    'type' => 'file',
                    'label' => 'Image :',
                    'id' => 'mediaNewChapitre',
                    'class' => 'inputRegister',
                    'error' => 'Image incorrecte',
                    'required' => false,
                ],
            ],
        ];
    }

}