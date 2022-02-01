<?php

namespace App\Model;
use App\Core\BaseSQL;

class User extends BaseSQL
{

    private $id = null;
    protected $email;
    protected $password;
    protected $firstname;
    protected $lastname;
    protected $status = null;
    protected $token = null;

    /**
     * @return mixed
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email): void
    {
        $this->email = strtolower(trim($email));
    }

    /**
     * @return mixed
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password): void
    {
        $this->password = password_hash($password, PASSWORD_DEFAULT);
    }

    /**
     * @return mixed
     */
    public function getFirstname(): string
    {
        return $this->firstname;
    }

    /**
     * @param mixed $firstname
     */
    public function setFirstname($firstname): void
    {
        $this->firstname = ucfirst(strtolower(trim($firstname)));
    }

    /**
     * @return mixed
     */
    public function getLastname(): string
    {
        return $this->lastname;
    }

    /**
     * @param mixed $lastname
     */
    public function setLastname($lastname): void
    {
        $this->lastname = strtoupper(trim($lastname));
    }

    /**
     * @return null
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * @param null $status
     */
    public function setStatus($status): void
    {
        $this->status = $status;
    }

    /**
     * @return null
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * @param null
     */
    public function generateToken(): void
    {
        $this->token = str_shuffle(md5(uniqid()));
    }

    public function save()
    {
        parent::save();
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
                'submit' => "S'inscrire",
            ],
            'inputs' => [
                'email' => [
                    'type' => 'email',
                    'placeholder' => 'Votre email',
                    'id' => 'emailRegister',
                    'class' => 'inputRegister',
                    'required' => true,
                    'error' => 'Email incorrect',
                    'errorUnicity' => 'Email existe déjà en bdd',
                    'unicity' => true,
                ],
                'password' => [
                    'type' => 'password',
                    'placeholder' => 'Votre mot de passe',
                    'id' => 'pwdRegister',
                    'class' => 'inputRegister',
                    'required' => true,
                    // changer la taille minimale du password
                    'error' => 'Votre mot de passe doit faire entre 8 et 16 caractères et contenir des chiffres et des lettres',
                ],
                'passwordConfirmation' => [
                    'type' => 'password',
                    'placeholder' => 'Confirmation du mot de passe',
                    'id' => 'pwdConfirmationRegister',
                    'class' => 'inputRegister',
                    'required' => true,
                    'confirm' => 'password',
                    // changer la taille minimale du password
                    'error' => 'Votre mot de passe de confirmation ne correspond pas',
                ],
                'firstname' => [
                    'type' => 'text',
                    'placeholder' => 'Prénom',
                    'id' => 'firstnameRegister',
                    'class' => 'inputRegister',
                    'min' => 2,
                    'max' => 50,
                    'error' => "Votre prénom n'est pas correct",
                ],
                'lastname' => [
                    'type' => 'text',
                    'placeholder' => 'Nom',
                    'id' => 'nameRegister',
                    'class' => 'inputRegister',
                    'min' => 2,
                    'max' => 100,
                    'error' => "Votre nom n'est pas correct",
                ],
                'description' => [
                    'type' => 'textarea',
                    'placeholder' => 'Description',
                    'id' => 'descriptionRegister',
                    'class' => 'inputRegister',
                    'cols' => 30,
                    'rows' => 10,
                    'error' => "Votre description n'est pas correct",
                    'required' => true,
                ],
                'pays' => [
                    'type' => 'select',
                    'placeholder' => 'Votre pays',
                    'id' => 'paysRegister',
                    'class' => 'inputRegister',
                    'options' => [
                        'France' => [
                            'value' => 'FRA',
                            'selected' => 'selected',
                        ],
                        'Espagne' => [
                            'value' => 'ESP',
                            'selected' => '',
                        ],
                        'États-Unis' => [
                            'value' => 'USA',
                            'selected' => '',
                        ],
                        'Australie' => [
                            'value' => 'AUS',
                            'selected' => '',
                        ],
                        'Japon' => [
                            'value' => 'JPN',
                            'selected' => '',
                        ],
                    ],
                ],
                "radio"=>[
                    "type"=>"radiobutton",
                    "id"=>"radioRegister",
                    "class"=>"inputRegister",
                    "error"=>"Veuillez choisir un champs",
                    "checked"=>"checked",
                    "value"=>[
                        "ILIES" => [
                            'label' => 'Iliès',
                            'checked' => '',
                        ],
                        "THOMAS" => [
                            'label' => 'Thomas',
                            'checked' => '',
                        ],
                        "GAEL" => [
                            'label' => 'Gaël',
                            'checked' => '',
                        ]
                    ],
                ],
                "checkbox"=>[
                    "type"=>"checkbox",
                    "id"=>"checkboxRegister",
                    "class"=>"inputRegister",
                    "required"=>true,
                    "label"=>"Conditions d'utilisations",
                    "value"=>[
                        "value" => 'CGU',
                        "checked"=> 'checked',
                    ],
                ],
                "file" => [
                    "type"=>"file",
                    "id"=>"fileRegister",
                    "class"=>"inputRegister",
                    "required"=>true,
                    "label"=>"Upload un fichier",
                ]
            ],
        ];
    }
}