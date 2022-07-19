<?php

namespace App\Model;

use App\Core\BaseSQL;
use App\Model\Media as MediaModel;
use App\Model\Page as PageModel;

class Personnage extends BaseSQL
{
    /** @var int|null $id */
    private $id = null;

    /** @var string|null $nom */
    protected $nom = null;

    /** @var int|null $media_id */
    protected $media_id = null;

    /** @var MediaModel|null $media */
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
        if(!empty($this->getMediaId())) {
            return (new MediaModel())->setId($this->getMediaId())->getStatut() === 2;
        }

        return false;
    }

    /**
     * @param int|null $media_id
     */
    public function setMediaId(?int $media_id): void
    {
        $this->media_id = $media_id;
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

    public function getPages()
    {
        return (new PageModel())->findManyBy(['personnage_id' => $this->getId(), 'statut' => 2]);
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

    public function toString()
    {
        return $this->getNom();
    }

    public function getPersonnageSelectOptions(): array
    {
        $personnages = $this->findManyBy(['statut' => 2]);
        $personnages_options = ['' => ''];
        foreach ($personnages as $personnage){
            $personnages_options[$personnage->getNom()] = $personnage->getId();
        }
        return $personnages_options;
    }

    public function getFormNewPersonnage()
    {
        return [
            'config' => [
                'method' => 'POST',
                'action' => '',
                'submit' => "Nouveau personnage",
                'title' => "Nouveau personnage",
            ],
            'inputs' => [
                'nom' => [
                    'type' => 'text',
                    'label' => 'Nom :',
                    'placeholder' => 'Nom',
                    'id' => 'nomNewPersonnage',
                    'class' => 'inputRegister',
                    'required' => true,
                    'min' => 2,
                    'max' => 65,
                    'error' => "Le nom est incorrect",
                ],
                'media_name' => [
                    'type' => 'text',
                    'label' => 'Nom image :',
                    'placeholder' => 'Nom image',
                    'id' => 'nomMediaNewPersonnage',
                    'class' => 'inputRegister',
                    'error' => 'nom incorrect',
                    'required' => false,
                ],
                'media' => [
                    'type' => 'file',
                    'label' => 'Image :',
                    'id' => 'mediaNewPersonnage',
                    'class' => 'inputRegister',
                    'error' => 'Image incorrecte',
                    'required' => false,
                ],
            ],
        ];
    }

    public function getFormUpdatePersonnage()
    {
        $this->setId($_GET['personnage_id']);

        $form = [
            'config' => [
                'method' => 'POST',
                'action' => '',
                'submit' => "Modifier personnage",
                'title' => "Modifier personnage",
            ],
            'inputs' => $this->getFormNewPersonnage()['inputs'],
        ];

        $form['inputs']['nom']['default_value'] = $this->getNom();
        $form['inputs']['media_name']['default_value'] = $this->getMedia()->getNom();
        $form['inputs']['statut'] =
            [
                'type' => 'select',
                'label' => 'Statut :',
                'options' =>
                    [
                        'Supprimé' => -1,
                        'Privé' => 1,
                        'Public' => 2,
                    ],
                'id' => 'statutUpdatePersonnage',
                'class' => 'inputRegister',
                'error' => "Impossible d'attribuer ce statut",
                'default_value' => $this->getStatut(),
            ];

        return $form;
    }
}
