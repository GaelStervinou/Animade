<?php

namespace App\Model;

use App\Core\BaseSQL;
use App\Core\Security;
use App\Model\Page as PageModel;
use JetBrains\PhpStorm\Pure;

class Categorie extends BaseSQL{
    /** @var int|null $id */
    private $id = null;

    /** @var int $parent_id */
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
     * @return int
     */
    public function getParentId(): ?int
    {
        return $this->parent_id;
    }

    /**
     * @return Categorie
     */
    public function getParent(): ?Categorie
    {
        if(!empty($this->getParentId())){
            $parent = new Categorie();
            $parent = $parent->setId($this->getParentId());
            return $parent;
        }
        return null;
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

    public function getPages()
    {
        $page = new PageModel();
        return $page->findManyBy(['categorie_id' => $this->getId(), 'statut' => 2]);
    }

    public function save()
    {
        return parent::save();
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

    #[Pure] public function toString(): string
    {
        return $this->getNom();
    }

    public function getCategorieSelectOptions()
    {
        $categories = $this->findManyBy(['statut' => 2]);
        $categories_options = [];
        foreach ($categories as $categorie){
            $categories_options[$categorie->getNom()] = $categorie->getId();
        }
        return $categories_options;
    }

    public function getFormNewCategorie(): array
    {
        $categories_options = $this->getCategorieSelectOptions();
        return [
            'config' => [
                'method' => 'POST',
                'action' => '',
                'submit' => "Nouvelle catégorie",
                'title' => "Nouvelle catégorie",
            ],
            'inputs' => [
                'nom' => [
                    'type' => 'text',
                    'label' => 'Nom :',
                    'placeholder' => 'Nom',
                    'id' => 'nomNewCategorie',
                    'class' => 'inputRegister',
                    'required' => true,
                    'min' => 2,
                    'max' => 50,
                    'error' => "Le nom est incorrect",
                ],
                'description' => [
                    'type' => 'textarea',
                    'label' => 'Description :',
                    'placeholder' => 'Description',
                    'id' => 'descriptionNewCategorie',
                    'class' => 'inputRegister',
                    'required' => true,
                    'min' => 2,
                    'max' => 250,
                    'rows' => 4,
                    'cols' => 35,
                    'error' => "Votre description est incorrect",
                ],
                'parent_id' => [
                    'type' => 'select',
                    'label' => 'Parent :',
                    'options' => $categories_options,
                    'id' => 'parentIdNewCategorie',
                    'class' => 'inputRegister',
                    'error' => "Impossible d'attribuer ce parent",
                ],
                'statut' => [
                    'type' => 'select',
                    'label' => 'Statut :',
                    'options' =>
                        [
                            'Supprimé' => -1,
                            'Privé' => 1,
                            'Public' => 2,
                        ],
                    'id' => 'statutNewCategorie',
                    'class' => 'inputRegister',
                    'required' => true,
                    'error' => "Impossible d'attribuer ce rôle",
                ],
            ],
        ];
    }
}