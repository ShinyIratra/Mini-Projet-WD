<?php

namespace app\repositories;

use flight\database\PdoWrapper;

class ArticleRepository
{
    protected PdoWrapper $db;

    public function __construct(PdoWrapper $db)
    {
        $this->db = $db;
    }

    public function getArticles(): array
    {
        return $this->db->query('SELECT * FROM article')->fetchAll();
    }
}