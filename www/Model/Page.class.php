<?php
namespace App\Model;
use App\Core\BaseSQL;
use App\Core\Security;
use App\Model\Commentaire;
use App\Model\Like;
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

    /** @var int|null $personnage_id */
    protected $personnage_id = null;

    /** @var int|null $media_id */
    protected $media_id = null;

    /** @var $date_creation */
    protected $date_creation = null;

    /** @var $date_modification */
    protected $date_modification = null;

    /** @var int|null $statut */
    protected $statut = null;

    /** @var int|null $chapitre_id */
    protected $chapitre_id = null;

    /** @var int|null $categorie_id */
    protected $categorie_id = null;

    /**
     * @return int|null
     */
    public function getCategorieId(): ?int
    {
        return $this->categorie_id;
    }

    /**
     * @param int|null $categorie_id
     */
    public function setCategorieId(?int $categorie_id): void
    {

        $this->categorie_id = $categorie_id;
    }

    public function getCategorie(): bool|Categorie
    {
        if($this->hasCategorie() === true){
            return (new Categorie())->setId($this->getCategorieId());
        }
        return false;

    }

    public function hasCategorie(): bool
    {
        if($this->getCategorieId() !== null) {
            return (new Categorie())->setId($this->getCategorieId())->getStatut() === 2;
        }
        return false;
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

    public function getAuteur()
    {
        $user = new User();
        $user = $user->setId($this->getAuteurId());
        return $user;
    }

    /**
     * @param int|null $auteur_id
     */
    public function setAuteurId(?int $auteur_id): void
    {
        $this->auteur_id = $auteur_id;
    }

    public function getPersonnage()
    {
        $personnage = new Personnage();
        $personnage = $personnage->setId($this->getPersonnageId());
        return $personnage;
    }

    /**
     * @return int|null
     */
    public function getPersonnageId(): ?int
    {
        return $this->personnage_id;
    }

    /**
     * @param int|null $personnage_id
     */
    public function setPersonnageId(?int $personnage_id): void
    {
        $this->personnage_id = $personnage_id;
    }

    /**
     * @return int|null
     */
    public function getMediaId(): ?int
    {
        return $this->media_id;
    }

    public function getMedia()
    {
        if(!empty($this->hasMedia())){
            $media = new MediaModel();
            $media = $media->setId($this->getMediaId());
            return $media;
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

    /**
     * @return int|null
     */
    public function getChapitreId(): ?int
    {
        return $this->chapitre_id;
    }

    public function getChapitre()
    {
        $chapitre = new Chapitre();
        $chapitre = $chapitre->setId($this->getChapitreId());
        return $chapitre;
    }

    /**
     * @param int|null $chapitre_id
     */
    public function setChapitreId(?int $chapitre_id): void
    {
        $this->chapitre_id = $chapitre_id;
    }

    public function getCommentaires()
    {
        return $this->hasMany(Commentaire::class);
    }

    public function countLikes()
    {
        $like = new Like();
        return count($like->findManyBy(['aime' => 1, 'page_id' => $this->getId()]));
    }

    public function countUnlikes()
    {
        $like = new Like();
        return count($like->findManyBy(['aime' => -1, 'page_id' => $this->getId()]));
    }

    public function currentUserLike()
    {
        $like = new Like();
        return $like->findOneBy($like->getTable(), ['user_id' => Security::getUser()->getId()]);
    }
    public function getMediaSelectOptions()
    {
        $media = new Media();
        $medias = $media->findManyBy(['user_id' => Security::getUser()->getId(), 'statut' => 2]);

        $medias_options = ['' => ''];
        foreach ($medias as $media){
            $medias_options[$media->getNom()] = $media->getId();
        }

        return $medias_options;
    }

    public function save()
    {
        parent::save();
    }

    public function __construct()
    {
        parent::__construct();
    }

    public function getFormNewPage(): array
    {
        $categorie = new Categorie();
        $categories_options = $categorie->getCategorieSelectOptions();
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
                    'default_value' => $this->getTitre(),
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
                    'default_value' => $this->getDescription(),
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
                    'default_value' => $this->getSlug(),
                ],
                'statut' => [
                    'type' => 'select',
                    'label' => 'Statut :',
                    'options' =>
                        [
                            'Supprimée' => -1,
                            'Accès restreint' => 1,
                            'Active' => 2,
                        ],
                    'id' => 'statutNewPage',
                    'class' => 'inputRegister',
                    'required' => true,
                    'error' => "Impossible d'attribuer ce statut",
                    'default_value' => $this->getStatut(),
                ],
                'categorie_id' => [
                    'type' => 'select',
                    'label' => 'Catégorie :',
                    'options' => $categories_options,
                    'id' => 'categorieIdNewPage',
                    'class' => 'inputRegister',
                    'error' => "Impossible d'attribuer cette catégorie",
                    'default_value' => $this->getCategorieId(),
                ],
                'chapitre_id' => [
                    'type' => 'select',
                    'label' => 'Chapitre :',
                    'options' => (new Chapitre())->getChapitreSelectOptions(),
                    'id' => 'categorieIdNewPage',
                    'class' => 'inputRegister',
                    'error' => "Impossible d'attribuer ce chapitre",
                    'default_value' => $this->getChapitreId(),
                ],
                'personnage_id' => [
                    'type' => 'select',
                    'label' => 'Personnage :',
                    'options' => (new Personnage())->getPersonnageSelectOptions(),
                    'id' => 'categorieIdNewPage',
                    'class' => 'inputRegister',
                    'error' => "Impossible d'attribuer ce personnage",
                    'default_value' => $this->getPersonnageId(),
                ],
                'media_name' => [
                    'type' => 'text',
                    'label' => 'Nom image :',
                    'placeholder' => 'Nom image',
                    'id' => 'nomMediaNewPage',
                    'class' => 'inputRegister',
                    'error' => 'nom incorrect',
                ],
                'media' => [
                    'type' => 'file',
                    'label' => 'Avatar :',
                    'id' => 'mediaNewPage',
                    'class' => 'inputRegisterMedia',
                    'error' => 'Image incorrecte',
                ],
                'contenu' => [
                    'type' => 'wysiwyg',
                    'label' => 'Contenu :',
                    'placeholder' => 'Vous pouvez rédiger votre article ici.',
                    'id' => 'contenuNewPage',
                    'required' => true,
                    'error' => 'Contenu incorrect',
                    'default_value' => $this->getContenu(),
                ],
            ],
        ];
    }

    public function getFormUpdatePage()
    {
        $form = $this->getFormNewPage();

        $form['config']['submit'] = "Modifier la page";
        $form['config']['title'] = "Modifier la page";

        $form['inputs']['select_media'] =
            [
                'type' => 'select',
                'label' => 'Image :',
                'options' => $this->getMediaSelectOptions(),
                'id' => 'selectMediaUpdatePage',
                'class' => 'inputRegister',
                'error' => 'Image incorrecte',
                'default_value' => $this->getMediaId(),
            ];
        return $form;
    }
}