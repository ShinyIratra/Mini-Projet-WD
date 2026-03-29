<?php

namespace app\controllers;

use app\repositories\ArticleRepository;
use app\services\ArticleService;
use flight\Engine;
use Throwable;

class FrontOfficeController
{
    protected Engine $app;

    public function __construct($app)
    {
        $this->app = $app;
    }

    public function home(): void
    {
        $articleRepository = new ArticleRepository($this->app->db());
        $articleService = new ArticleService($articleRepository);
        $articles = $articleService->getArticles();

        $this->app->render('frontoffice/home', [
            'articles' => $articles,
        ]);
    }
}