<?php

namespace App\Model;

use App\Core\BaseSQL;
use App\Helpers\UrlHelper;

class Like extends BaseSQL{

    /** @var int|null $id */
    private $id = null;

    /** @var int|null $aime */
    protected $aime = null;

    /** @var int|null $user_id */
    protected $user_id = null;

    /** @var int|null $page_id */
    protected $page_id = null;

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
    public function getAime(): ?int
    {
        return $this->aime;
    }

    /**
     * @param int|null $aime
     */
    public function setAime(?int $aime): void
    {
        $this->aime = $aime;
    }

    /**
     * @return int|null
     */
    public function getUserId(): ?int
    {
        return $this->user_id;
    }

    /**
     * @param int|null $user_id
     */
    public function setUserId(?int $user_id): void
    {
        $this->user_id = $user_id;
    }

    /**
     * @return int|null
     */
    public function getPageId(): ?int
    {
        return $this->page_id;
    }

    /**
     * @param int|null $page_id
     */
    public function setPageId(?int $page_id): void
    {
        $this->page_id = $page_id;
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