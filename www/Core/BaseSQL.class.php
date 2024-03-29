<?php


namespace App\Core;

abstract class BaseSQL
{
    protected $pdo;
    protected $table;

    public function __construct()
    {
        //Intégrer singleton

        try {
            $this->pdo = new \PDO("mysql:host=".DBHOST.";port=".DBPORT.";dbname=".DBNAME,DBUSER,DBPWD);
            $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        }catch(\Exception $e){
            die("Erreur SQL ".$e->getMessage());
        }
        // récupérer le nom de la table ( = préfixe + nom de la classe enfant )
        $classExploded = explode("\\", get_called_class());
        $this->table = DBPREFIX.strtolower(end($classExploded));

    }


    protected function save()
    {
        $varsToExclude = get_class_vars(get_class());
        $columns = get_object_vars($this);
        $columns = array_diff_key($columns, $varsToExclude);
        $columns = array_filter($columns);

        if(!is_null($this->getId())) {
            foreach($columns as $key => $value)
            {
                $setUpdate[] = $key."=:".$key;
            }
            $sql = "UPDATE ".$this->table. " SET ".implode(",", $setUpdate)." WHERE id=".$this->getId();
        }else{
            $sql = "INSERT INTO ".$this->table."(".implode(",", array_keys($columns)).")
        VALUES(:".implode(",:", array_keys($columns)).")";
        }

        $queryPrepared = $this->pdo->prepare($sql);
        $queryPrepared->execute( $columns );
    }

    public function setId($id): object
    {
        $sql = "SELECT * FROM ".$this->table." WHERE id=:id";

        // select * from user where
        $queryPrepared = $this->pdo->prepare($sql);
        $queryPrepared->execute(['id' => $id]);
        return $queryPrepared->fetchObject(get_called_class());
    }

}