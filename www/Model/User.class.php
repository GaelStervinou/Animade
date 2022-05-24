<?php

namespace App\Model;
use App\Core\BaseSQL;
use App\Model\Role;

class User extends BaseSQL
{
    
    /** @var int|null $id */
    private $id = null;
    
    /** @var int|null $role_id */
    protected $role_id = null;

    /** @var RoleModel|null $role */
    protected $role = null;

    /** @var string $email */
    protected $email;

    /** @var string $password */
    protected $password;

    /** @var string $firstname */
    protected $firstname;

    /** @var string $lastname */
    protected $lastname;

    /** @var int|null $status */
    protected $status = null;

    /** @var string|null $token */
    protected $token = null;

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
    public function getRoleId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $role_id
     *
     */
    public function setRoleId(?int $role_id): void
    {
        $this->role_id = $role_id;
    }

    public function getRole(): ?RoleModel
    {
        // ne pas oublier de créer une fonction __toString() dans la table RoleModel pour pouvoir l'écrire ?
        if ($this->role instanceof RoleModel) {
            return $this->role;
        }

        return null;
    }

    /**
     * @return string
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
     * @return string
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
     * @return string
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
     * @return string
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
     * @return int|null
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
     * @return string
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

    public function login($email, $password)
    {
        return parent::login($email, $password);
    }

    public function __construct()
    {
        parent::__construct();
    }

    public function getFormLogin(): array
    {
        return [
            'config' => [
                'method' => 'POST',
                'action' => '',
                'submit' => "Se connecter",
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
                ]
            ],
        ];
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
                'firstname' => [
                    'type' => 'text',
                    'placeholder' => 'Prénom',
                    'label' => 'Prénom',
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
            ],
        ];
    }
}