<?php

namespace app\controllers;

use app\repositories\ArticleRepository;
use app\services\ArticleService;
use flight\Engine;

class BackOfficeController
{
    protected Engine $app;

    public function __construct($app)
    {
        $this->app = $app;
    }

    public function article_detail($id): void
    {
        $articleRepository = new ArticleRepository($this->app->db());
        $articleService = new ArticleService($articleRepository);
        $articles = $articleService->getArticles();

        $this->app->render('backoffice/home', [
            'articles' => $articles,
        ]);
    }

    public function ajouter_article()
    {
        $repo = new ArticleRepository($this->app->db());
        $service = new ArticleService($repo);
        $data = [
            'categories' => $service->getAllCategories(),
            'utilisateurs' => $service->getAllUtilisateurs()
        ];
        $this->app->render('backoffice/ajouter_article', $data);
    }

    public function traitement_ajout_article()
    {
        $titre = $this->app->request()->data->titre;
        $contenu = $this->app->request()->data->contenu;
        $id_categorie = $this->app->request()->data->id_categorie;

        $repo = new ArticleRepository($this->app->db());
        $service = new ArticleService($repo);
        $id_article = $service->insertArticle($titre, $contenu, $id_categorie);

        // Upload photos
        if (isset($_FILES['photos'])) {
            foreach ($_FILES['photos']['tmp_name'] as $key => $tmp) {
                if (!empty($tmp) && $_FILES['photos']['error'][$key] === 0) {
                    $uploadDir = 'public/uploads/articles/';
                    if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

                    $nom = basename($_FILES['photos']['name'][$key]);
                    $chemin = '/uploads/articles/' . $nom;
                    $destination = $uploadDir . $nom;

                    // Alt fourni par l'utilisateur
                    $alt = isset($_POST['photos_alt'][$key]) && !empty($_POST['photos_alt'][$key])
                           ? $_POST['photos_alt'][$key]
                           : $nom;

                    if (move_uploaded_file($tmp, $destination)) {
                        $service->insertPhoto($id_article, $chemin, $alt);
                    }
                }
            }
        }

        // Ajouter auteur
        if (isset($this->app->request()->data->id_auteur) && !empty($this->app->request()->data->id_auteur)) {
            $id_auteur = (int)$this->app->request()->data->id_auteur;
            $service->insertAuteur($id_article, $id_auteur);
        }

        $this->app->redirect('/backoffice/liste_articles');
    }

    public function liste_articles()
    {
        $repo = new ArticleRepository($this->app->db());
        $service = new ArticleService($repo);
        $articles = $service->getArticles();

        // Charger les photos pour chaque article
        foreach ($articles as &$article) {
            $article['photos'] = $repo->getPhotosByArticle($article['id_article']);
        }

        $this->app->render('backoffice/articles', ['articles' => $articles]);
    }

    public function modif_article()
    {
        $id = $this->app->request()->query->id_article;
        $repo = new ArticleRepository($this->app->db());
        $service = new ArticleService($repo);
        $data = [
            'article' => $service->getArticleById($id),
            'categories' => $service->getAllCategories(),
            'utilisateurs' => $service->getAllUtilisateurs()
        ];
        $this->app->render('backoffice/modifier_article', $data);
    }

    public function traitement_modif_article()
    {
        $id = $this->app->request()->data->id_article;
        $titre = $this->app->request()->data->titre;
        $contenu = $this->app->request()->data->contenu;
        $id_categorie = $this->app->request()->data->id_categorie;

        $repo = new ArticleRepository($this->app->db());
        $service = new ArticleService($repo);
        $service->updateArticle($id, $titre, $contenu, $id_categorie);

        // Suppression auteurs et réinsertion
        $service->deleteAllAuteurs($id);
        if (isset($this->app->request()->data->id_auteur) && !empty($this->app->request()->data->id_auteur)) {
            $id_auteur = (int)$this->app->request()->data->id_auteur;
            $service->insertAuteur($id, $id_auteur);
        }

        // Supprimer photos marquées
        if (isset($this->app->request()->data->photos_a_supprimer)) {
            foreach ($this->app->request()->data->photos_a_supprimer as $id_photo => $val) {
                if ($val) $service->deletePhoto($id_photo);
            }
        }

        // Upload nouvelles photos
        if (isset($_FILES['photos'])) {
            foreach ($_FILES['photos']['tmp_name'] as $key => $tmp) {
                if (!empty($tmp) && $_FILES['photos']['error'][$key] === 0) {
                    $uploadDir = 'public/uploads/articles/';
                    if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

                    $nom = basename($_FILES['photos']['name'][$key]);
                    $chemin = '/uploads/articles/' . $nom;
                    $destination = $uploadDir . $nom;

                    // Alt fourni par l'utilisateur
                    $alt = isset($_POST['photos_alt'][$key]) && !empty($_POST['photos_alt'][$key])
                           ? $_POST['photos_alt'][$key]
                           : $nom;

                    if (move_uploaded_file($tmp, $destination)) {
                        $service->insertPhoto($id, $chemin, $alt);
                    }
                }
            }
        }

        $this->app->redirect('/backoffice/liste_articles');
    }

    public function traitement_supprimer_article()
    {
        $id = $this->app->request()->query->id_article;
        $repo = new ArticleRepository($this->app->db());
        $service = new ArticleService($repo);
        $service->deleteArticle($id);
        $this->app->redirect('/backoffice/liste_articles');
    }
}
