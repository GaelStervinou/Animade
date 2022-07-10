<?php

namespace App\Model;
use App\Core\BaseSQL;

class Role extends BaseSQL
{
    /** @var int */
    private $id = null;
    /** @var string */
    protected $name;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    public function save()
    {
        parent::save();
    }

    public function __construct()
    {
        parent::__construct();
    }
}