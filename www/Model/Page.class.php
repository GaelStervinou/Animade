<?php
namespace App\Model;
use App\Core\BaseSQL;
use App\Model\Media as MediaModel;

class Page extends BaseSQL
{
    /** @var int|null $id */
    private $id = null;

    /** @var string|null $titre */
    protected $titre = null;

    /** @var string|null $contenu */
    protected $contenu = null;

    /** @var string|null $description */
    protected $description = null;

    /** @var string|null $slug */
    protected $slug = null;

    /** @var int|null $auteur_id */
    protected $auteur_id = null;

    /** @var int|null $peronnsage_id */
    protected $peronnsage_id = null;

    /** @var int|null $media_id */
    protected $media_id = null;

    /** @var $date_creation */
    protected $date_creation = null;

    /** @var $date_modification */
    protected $date_modification = null;

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
     * @param int|null $id
     */
    public function setId(?int $id): void
    {
        $this->id = $id;
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
    public function getSlug(): ?string
    {
        return $this->slug;
    }

    /**
     * @param string|null $slug
     */
    public function setSlug(?string $slug): void
    {
        $this->slug = $slug;
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
     * @return int|null
     */
    public function getPeronnsageId(): ?int
    {
        return $this->peronnsage_id;
    }

    /**
     * @param int|null $peronnsage_id
     */
    public function setPeronnsageId(?int $peronnsage_id): void
    {
        $this->peronnsage_id = $peronnsage_id;
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
     * @return mixed
     */
    public function getDateCreation()
    {
        return $this->date_creation;
    }

    /**
     * @param mixed $date_creation
     */
    public function setDateCreation($date_creation): void
    {
        $this->date_creation = $date_creation;
    }

    /**
     * @return mixed
     */
    public function getDateModification()
    {
        return $this->date_modification;
    }

    /**
     * @param mixed $date_modification
     */
    public function setDateModification($date_modification): void
    {
        $this->date_modification = $date_modification;
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

    public function __construct()
    {
        parent::__construct();
    }

    public function getFormRegister(): array
    {
        return [
            'config' => [
                'method' => 'POST',
                'action' => '',
                'submit' => "Nouvelle page",
                'title' => "Nouvelle page",
            ],
            'inputs' => [
                'titre' => [
                    'type' => 'text',
                    'label' => 'Titre :',
                    'placeholder' => 'Titre',
                    'id' => 'titreNewPage',
                    'class' => 'inputRegister',
                    'required' => true,
                    'min' => 2,
                    'max' => 100,
                    'error' => "Le titre est incorrect",
                ],
                'description' => [
                    'type' => 'text',
                    'label' => 'Description :',
                    'placeholder' => 'Description',
                    'id' => 'descriptionNewPage',
                    'class' => 'inputRegister',
                    'min' => 2,
                    'max' => 250,
                    'error' => "Votre description est incorrect",
                ],
                'slug' => [
                    'type' => 'text',
                    'label' => 'Slug :',
                    'placeholder' => 'Slug',
                    'id' => 'slugNewPage',
                    'class' => 'inputRegister',
                    'required' => true,
                    'error' => 'Slug incorrect',
                    'errorUnicity' => 'Ce slug existe déjà',
                    'unicity' => true,
                ],
                ''
            ],
        ];
    }
}