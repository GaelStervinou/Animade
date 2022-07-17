<?php


namespace App\Core;

use App\Core\QueryBuilder;
use App\Helpers\UrlHelper;
use App\Model\Commentaire;

class BaseSQL implements QueryBuilder
{
    private $pdo;
    protected $table;
    private $query;

    public function getTable()
    {
        return $this->table;
    }
    public function __construct()
    {
        // Intégrer singleton
        // cf exo design pattern

        try {
            $this->pdo = new \PDO("mysql:host=" . DBHOST . ";port=" . DBPORT . ";dbname=" . DBNAME . ";charset=utf8mb4", DBUSER, DBPWD);
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
        return $this->pdo->lastInsertId();
    }

    public function setId($id)
    {
        return $this->findOneBy($this->table, ['id' => $id]);
    }

    public function login($email, $password)
    {
        $sql = "SELECT password FROM " . $this->table . " WHERE email =:email";
        $queryPrepared = $this->pdo->prepare($sql);
        $queryPrepared->execute(['email' => $email]);
        $res = $queryPrepared->fetch()["password"];
        return password_verify($password, $res);
    }

    public function emailVerification()
    {
        ['emailToken' => $email_token, 'email' => $user_email ] = $_GET;
        if(empty($email_token)){
            Security::returnError(404);
        }
        return $this->findOneBy($this->getTable(), ['email' => $user_email, 'emailToken' => $email_token]);
    }

    public function getUserFromEmail(string $email)
    {
        return $this->findOneBy($this->table, ['email' => $email]);
    }

    //modifier pour avoir une fonction générique à tous les modèles ( getOneBy() );

    public function findBy(array $options, string $table)
    {
        $parameters = ['table' => $table];
        $where = $options['where'];
        //var_dump($options);die;
        foreach ($options['where'] as $key => $value) {
            $parameters[$key] = $value;
        }

//var_dump(implode(",", $where), $where);die;
        $sql = "SELECT id FROM " . DBPREFIX . ":table WHERE " . implode(",", $where);
//var_dump($parameters, $sql);die;
        $queryPrepared = $this->pdo->prepare($sql);
        $queryPrepared->execute($parameters);

        $objectList = [];
        foreach($queryPrepared->fetch() as $object){
            $objectList[] = $object->fetchObject();
        }
        //$id = $queryPrepared->fetch()["id"];
return $objectList;
        //return $this->setId($id, "App\Model\User");
    }

    public function findPagesBy($parameters)
    {
        $where = [];
        var_dump($_GET);die;
    }

    public function delete()
    {
        try {
            $this->beginTransaction();
            $this->setStatut(-1);
            $this->save();
            $this->commit();
        }catch (Exception $e){
            $this->rollback();
            var_dump($e->getMessage());die;
        }
    }

    public function getPageFromSlug($slug)
    {
        return $this->findOneBy($this->table, ['slug' => $slug]);
    }

    public function getPersonnageFromNom($nom)
    {
        return $this->findOneBy($this->table, ['nom' => $nom]);
    }

    public function findOneBy(string $table, array $where, array $orderBy=null)
    {
        $this->select($table, ['*']);
        foreach ($where as $column => $value){
            if(is_array($value)) {
                $this->where($column, str_replace("'", "\'", $value['value']), $value['operator']);
            }else{
                $this->where($column, str_replace("'", "\'", $value));
            }
        }
        if($orderBy !== null){
            $this->orderBy($orderBy[0], $orderBy[1]);
        }
        return $this->fetchQuery(get_called_class(), 'one');
    }

    public function findManyBy(array $where, array $orderBy = null, array $limit = null)
    {
        try{
            $this->select($this->table, ['*']);
            foreach ($where as $column => $value){
                if(is_array($value)) {
                    $this->where($column, str_replace("'", "\'", $value['value']), $value['operator']);
                }else{
                    $this->where($column, str_replace("'", "\'", $value));
                }
            }
            if($orderBy !== null){
                $this->orderBy($orderBy[0], $orderBy[1]);
            }
            if($limit !== null){
                $this->limit($limit[0], $limit[1]);
            }
            $objects =  $this->fetchQuery(get_called_class());
            return $objects;
        }catch(\Error $e){
            die('Error');
        }
    }

    public function hasMany($class, $foreignKey=null)
    {
        if($foreignKey === null){
            $classExploded = explode("\\", get_called_class());
            $foreignKey = strtolower(end($classExploded))."_id";
        }
        $table = $classExploded = explode("\\", $class);
        $table = DBPREFIX.strtolower(end($table));

        $this->select($table, ['*']);
        $this->where($foreignKey, $this->getId());
        return $this->fetchQuery($class);
    }

    public function getTableFromClass($class)
    {
        $classExploded = explode("\\", $class);
        return DBPREFIX.strtolower(end($classExploded));
    }

    public function checkIfCanResponseToComment($comment_id)
    {
        $this
            ->select(DBPREFIX.'commentaire', ['commentaire_id', 'auteur_id'])
            ->where('id', $comment_id);
        $query = $this->prepareQuery();
        $query->execute();
        $commentaire = $query->fetch();
        if($commentaire['commentaire_id'] !== null || $commentaire['auteur_id'] === Security::getUser()->getId()){
            return Security::return403("Vous ne pouvez pas répondre à ce commentaire");
        }
        return true;
    }







    public function init() {
        $this->query = new \StdClass;
    }

    public function insert (string $table, array $values) : QueryBuilder {
        return true;
    }

    public function updateTable(string $table, array $primaryKeys, array $valuesToUpdate) : QueryBuilder {
        $this->init();
        $finalValues = [];
        foreach($valuesToUpdate as $column => $value){
            $finalValues[] = $column . "='" . $value."'";
        }
        $this->query->base = "UPDATE ".$table." SET " . implode(',', $finalValues);
        foreach($primaryKeys as $column => $value){
            $this->where($column, $value);
        }
        return $this;
    }

    public function select (string $table, array $columns): QueryBuilder {
        $this->init();

        $this->query->base = "SELECT " . implode(',', $columns) . " FROM ".$table;
        return $this;
    }

    public function where (string $column, string $value, string $operator = '='): QueryBuilder {

        $this->query->where[] = ' ' . $column . $operator . "'" . $value . "'" ;

        return $this;
    }

    public function orderBy (string $column, string $order): QueryBuilder {
        $this->query->orderBy = ' ORDER BY ' . $column . ' ' . $order;
        return $this;
    }

    public function limit (int $from, int $offset): QueryBuilder {
        $this->query->limit = ' LIMIT ' . $from . ', ' . $offset;
        return $this;
    }

    public function getQuery (): string {

        $sql = $this->query->base;

        if(!empty($this->query->where)) {
            $sql .= " WHERE " . implode(" AND ",$this->query->where);
        }

        if(isset($this->query->orderBy)){
            $sql .= " " . $this->query->orderBy;
        }

        if(isset($this->query->limit)) {
            $sql .= " " . $this->query->limit;
        }

        $sql .= ';';

        return $sql;
    }

    public function fetchQuery($class=null, $fetch_type=null)
    {
        $query = $this->prepareQuery();
        if(!is_null($class)){
            $query->setFetchMode(\PDO::FETCH_CLASS, $class);
        }
        $query->execute();
        if($fetch_type === 'one'){
            return $query->fetch();
        }
        return $query->fetchAll();

    }

    public function prepareQuery()
    {
        $res = $this->getQuery();
        return $this->pdo->prepare($res);
    }

    public function beginTransaction()
    {
        $this->pdo->beginTransaction();
    }

    public function commit()
    {
        $this->pdo->commit();
    }

    public function rollBack()
    {
        $this->pdo->rollBack();
    }

}
