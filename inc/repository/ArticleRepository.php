<?php

namespace inc\repository;

use PDO;

class ArticleRepository
{
    protected PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function getArticles(): array
    {
        return $this->db->query('SELECT * FROM article')->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTitreArticles(): array
    {
        $sql = "
            SELECT
                id_article,
                titre,
                date_publication,
                id_categorie,
                SUBSTRING(REGEXP_REPLACE(contenu, '<[^>]*>', '', 'g'), 1, 200) || '...' AS contenu
            FROM article
            ORDER BY date_publication DESC
        ";

        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getArticleById($id)
    {
        $requete = "SELECT * FROM article WHERE id_article = ?";
        $stmt = $this->db->prepare($requete);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function insertArticle($titre, $contenu, $id_categorie)
    {
        $requete = "INSERT INTO article (titre, contenu, date_publication, id_categorie) VALUES (?, ?, NOW(), ?)";
        $stmt = $this->db->prepare($requete);
        $stmt->execute([$titre, $contenu, $id_categorie]);
        return $this->db->lastInsertId();
    }

    public function updateArticle($id, $titre, $contenu, $id_categorie)
    {
        $requete = "UPDATE article SET titre = ?, contenu = ?, id_categorie = ? WHERE id_article = ?";
        $stmt = $this->db->prepare($requete);
        $stmt->execute([$titre, $contenu, $id_categorie, $id]);
    }

    public function deleteArticle($id)
    {
        $this->db->prepare("DELETE FROM article_photo WHERE id_article = ?")->execute([$id]);
        $this->db->prepare("DELETE FROM auteur WHERE id_article = ?")->execute([$id]);
        $this->db->prepare("DELETE FROM article WHERE id_article = ?")->execute([$id]);
    }

    public function getPhotosByArticle($id)
    {
        $requete = "SELECT * FROM article_photo WHERE id_article = ?";
        $stmt = $this->db->prepare($requete);
        $stmt->execute([$id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function insertPhoto($id_article, $chemin, $alt)
    {
        $requete = "INSERT INTO article_photo (id_article, chemin, alt) VALUES (?, ?, ?)";
        $stmt = $this->db->prepare($requete);
        $stmt->execute([$id_article, $chemin, $alt]);
    }

    public function deletePhoto($id_photo)
    {
        $requete = "DELETE FROM article_photo WHERE id_article_photo = ?";
        $stmt = $this->db->prepare($requete);
        $stmt->execute([$id_photo]);
    }

    public function getAuteursByArticle($id)
    {
        $requete = "SELECT u.id_utilisateur, u.nom, u.prenom FROM auteur a
                   JOIN utilisateur u ON a.id_utilisateur = u.id_utilisateur
                   WHERE a.id_article = ?";
        $stmt = $this->db->prepare($requete);
        $stmt->execute([$id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function insertAuteur($id_article, $id_utilisateur)
    {
        $requete = "INSERT INTO auteur (id_article, id_utilisateur) VALUES (?, ?)";
        $stmt = $this->db->prepare($requete);
        $stmt->execute([$id_article, $id_utilisateur]);
    }

    public function deleteAllAuteurs($id_article)
    {
        $requete = "DELETE FROM auteur WHERE id_article = ?";
        $stmt = $this->db->prepare($requete);
        $stmt->execute([$id_article]);
    }

    public function getAllCategories()
    {
        return $this->db->query('SELECT id_categorie, rubrique FROM categorie ORDER BY rubrique')->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllUtilisateurs()
    {
        return $this->db->query('SELECT id_utilisateur, nom, prenom FROM utilisateur ORDER BY nom')->fetchAll(PDO::FETCH_ASSOC);
    }
}
