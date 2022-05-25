<?php


namespace App\Core;

abstract class BaseSQL
{
    protected $pdo;
    protected $table;

    public function __construct()
    {
        // Intégrer singleton
        // cf exo design pattern

        try {
            $this->pdo = new \PDO("mysql:host=" . DBHOST . ";port=" . DBPORT . ";dbname=" . DBNAME, DBUSER, DBPWD);
            $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (\Exception $e) {
            die("Erreur SQL " . $e->getMessage());
        }
        // récupérer le nom de la table ( = préfixe + nom de la classe enfant )
        $classExploded = explode("\\", get_called_class());
        $this->table = DBPREFIX . strtolower(end($classExploded));
    }


    protected function save()
    {
        $varsToExclude = get_class_vars(get_class());
        $columns = get_object_vars($this);
        $columns = array_diff_key($columns, $varsToExclude);
        $columns = array_filter($columns);
        if (!is_null($this->getId())) {
            foreach ($columns as $key => $value) {
                $setUpdate[] = $key . "=:" . $key;
            }
            $sql = "UPDATE " . $this->table . " SET " . implode(",", $setUpdate) . " WHERE id=" . $this->getId();
        } else {
            $sql = "INSERT INTO " . $this->table . "(" . implode(",", array_keys($columns)) . ")
        VALUES(:" . implode(",:", array_keys($columns)) . ")";
        }
        $queryPrepared = $this->pdo->prepare($sql);
        $queryPrepared->execute($columns);
    }

    public function setId($id): object
    {
        $sql = "SELECT * FROM " . $this->table . " WHERE id=:id";

        $queryPrepared = $this->pdo->prepare($sql);
        $queryPrepared->execute(['id' => $id]);
        return $queryPrepared->fetchObject(get_called_class());
    }

    public function login($email, $password)
    {
        $sql = "SELECT password FROM " . $this->table . " WHERE email =:email";
        $queryPrepared = $this->pdo->prepare($sql);
        $queryPrepared->execute(['email' => $email]);
        $res = $queryPrepared->fetch()["password"];
        return password_verify($password, $res);
    }

    public function emailVerification($tokenVerification)
    {
        // $sql = "SELECT id FROM ".$this->table." WHERE emailToken = ". $tokenVerification;
        // $queryPrepared = $this->pdo->prepare($sql);

        $iduser = $_GET['id'];
        $emailToken = $_GET['emailToken'];
        $recupUser = $this->pdo->prepare('SELECT * FROM zdek_user WHERE id = ? AND emailToken = ?');
        $recupUser->execute(array($iduser, $emailToken));

        if ($recupUser->rowCount() > 0) {
            $userInfo = $recupUser->fetch();
            if ($userInfo['status'] != 1) {
                $updateStatus = $this->pdo->prepare('UPDATE zdek_user SET status = ? WHERE id = ?');
                $updateStatus->execute(array(1, $iduser));
            }
        }

        echo 'L\'id user est ' . $iduser . ' et le token de l\'email est : ' . $emailToken;
    }
}
