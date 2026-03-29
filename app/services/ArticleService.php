<?php

namespace app\services;

use app\repositories\ArticleRepository;
use RuntimeException;

class ArticleService
{
    protected ArticleRepository $articleRepository;

    public function __construct(ArticleRepository $articleRepository)
    {
        $this->articleRepository = $articleRepository;
    }

    public function getArticles(): array
    {
        return $this->articleRepository->getArticles();
    }   
}