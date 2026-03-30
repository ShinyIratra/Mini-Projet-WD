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

    public function getTitreArticles(): array
    {
        $sql = "
            SELECT 
                Id_Article, 
                titre, 
                date_publication, 
                Id_Categorie,
                LEFT(REGEXP_REPLACE(contenu, '<[^>]*>', '', 'g'), 200) || '...' AS contenu
            FROM Article
            ORDER BY date_publication DESC
        ";

        return $this->db->fetchAll($sql);
    }

    public function getArticleById($id)
    {
        $requete = "SELECT * FROM article WHERE id_article = ?";
        $stmt = $this->db->prepare($requete);
        $stmt->execute([$id]);
        return $stmt->fetch();
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
        $this->db->query("DELETE FROM article_photo WHERE id_article = ?", [$id]);
        $this->db->query("DELETE FROM auteur WHERE id_article = ?", [$id]);
        $this->db->query("DELETE FROM article WHERE id_article = ?", [$id]);
    }

    public function getPhotosByArticle($id)
    {
        $requete = "SELECT * FROM article_photo WHERE id_article = ?";
        $stmt = $this->db->prepare($requete);
        $stmt->execute([$id]);
        return $stmt->fetchAll();
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
        return $stmt->fetchAll();
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
        return $this->db->query('SELECT id_categorie, rubrique FROM categorie ORDER BY rubrique')->fetchAll();
    }

    public function getAllUtilisateurs()
    {
        return $this->db->query('SELECT id_utilisateur, nom, prenom FROM utilisateur ORDER BY nom')->fetchAll();
    }
}
