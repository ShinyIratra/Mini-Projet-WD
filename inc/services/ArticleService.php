<?php

namespace inc\services;

use inc\repository\ArticleRepository;
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

    public function getTitreArticles(): array
    {
        return $this->articleRepository->getTitreArticles();
    }
    
    public function getArticleById($id)
    {
        $article = $this->articleRepository->getArticleById($id);
        if ($article) {
            $article['photos'] = $this->articleRepository->getPhotosByArticle($id);
            $article['auteurs'] = $this->articleRepository->getAuteursByArticle($id);
        }
        return $article;
    }

    public function insertArticle($titre, $contenu, $id_categorie)
    {
        return $this->articleRepository->insertArticle($titre, $contenu, $id_categorie);
    }

    public function updateArticle($id, $titre, $contenu, $id_categorie)
    {
        $this->articleRepository->updateArticle($id, $titre, $contenu, $id_categorie);
    }

    public function deleteArticle($id)
    {
        $this->articleRepository->deleteArticle($id);
    }

    public function insertPhoto($id_article, $chemin, $alt)
    {
        $this->articleRepository->insertPhoto($id_article, $chemin, $alt);
    }

    public function deletePhoto($id_photo)
    {
        $this->articleRepository->deletePhoto($id_photo);
    }

    public function insertAuteur($id_article, $id_utilisateur)
    {
        $this->articleRepository->insertAuteur($id_article, $id_utilisateur);
    }

    public function deleteAllAuteurs($id_article)
    {
        $this->articleRepository->deleteAllAuteurs($id_article);
    }

    public function getAllCategories()
    {
        return $this->articleRepository->getAllCategories();
    }

    public function getAllUtilisateurs()
    {
        return $this->articleRepository->getAllUtilisateurs();
    }
}