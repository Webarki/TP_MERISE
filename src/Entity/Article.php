<?php

namespace App\src\Entity;

use App\src\Entity\Database;
use PDO;

class Article extends Database
{
    public $id;
    public $title;
    public $content;
    public $img;
    public $createdAt;
    public $state;
    public $modify_at;
    public $id_user;
    public $id_category;


    public function __construct()
    {
        parent::__construct();
    }


    /**
     * methode permettant de recuperer la liste des articles
     */
    public function getArticleList()
    {
        $query = 'SELECT `id`,`title`,`content`, `img` , `state`,DATE_FORMAT(`created_at`,\'%e/%m/%Y\') AS `createdAt`, `modify_at` AS `modifyAt`, `id_user` AS idUser, `id_category` AS `idCategory` FROM `article`';
        $queryResult = $this->db->query($query);
        return $queryResult->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * methode permettant de recuperer la liste des articles
     */
    public function getArticles()
    {
        $query = 'SELECT * FROM `article`';
        $queryResult = $this->db->query($query);
        return $queryResult->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * methode permettant d'afficher la liste des 5 premier article
     */
    public function getArticleListLimit5()
    {
        $query = 'SELECT `id`,`title`,`content`, `img` , `state`,DATE_FORMAT(`created_at`,\'%e/%m/%Y\') AS `createdAt` FROM `article` LIMIT 5';
        $queryResult = $this->db->query($query);
        return $queryResult->fetchAll(PDO::FETCH_OBJ);
    }



    /**
     * methode permettant de recuperer les articles dont le statut est true
     */
    public function getArticleListStateTrue()
    {
        $query = 'SELECT * FROM `article` WHERE `state`=1';
        $queryResult = $this->db->query($query);
        return $queryResult->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * methode permettant de recuperer la liste des article d'une category
     */
    public function getArticleListByCategory()
    {
        $query = 'SELECT `title`, `content`, `state`, DATE_FORMAT(`created_at`, "%d/%m/%Y") AS `createdAt`, `modify_at`, `img`, `id_user`, `id_category` FROM `article` WHERE `id_category`=:id_category';
        $findProfil = $this->db->prepare($query);
        $findProfil->bindValue(':id_category', $this->id_category, PDO::PARAM_INT);
        $findProfil->execute();
        return $findProfil->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Methode qui permet de recuperer un articles par son id
     */
    public function getArticleById()
    {
        $requete = 'SELECT * FROM `article` WHERE `id`=:id ;';
        $find = $this->db->prepare($requete);
        $find->bindValue(':id', $this->id, PDO::PARAM_INT);
        if ($find->execute()) {
            return $find->fetch(PDO::FETCH_OBJ);
        }
    }

    /**
     * Methode qui permet de recuperer un article, son auteur, sa category et ses commentaires
     */
    public function getArticleAuthorCategoryComment()
    {
        $requete = 'SELECT 
        article.title as article,
        user.email as author,
        category.title as category,
        comment.content as comment FROM `article`
        INNER JOIN `user` ON article.id_user=user.id
        INNER JOIN `category` ON article.id_category=category.id 
        INNER JOIN `comment` ON article.id=comment.id_article;';
        $find = $this->db->query($requete);
        return $find->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     *
     * retourn le nombre d'article en base de donnée
     */
    public function lastId()
    {
        $query = 'SELECT MAX( id ) AS id FROM `article`;';
        $count = $this->db->query($query);
        return $count->fetch();
    }

    /**
     *
     * retourn le nombre d'article en base de donnée
     */
    public function count()
    {
        $query = 'SELECT * FROM `article`;';
        $count = $this->db->query($query);
        return $count->rowCount();
    }

    /**
     * Method qui sert a inserer un article
     * @return requete INSERT
     */
    public function createArticle()
    {
        $insert = 'INSERT INTO `article` (`content`, `title`, `img`, `state`, `id_category`, `id_user`) VALUES (:content, :title, :img, :state, :id_category, :id_user);';
        $insertDb = $this->db->prepare($insert);
        $insertDb->bindValue(':content', $this->content, PDO::PARAM_STR);
        $insertDb->bindValue(':title', $this->title, PDO::PARAM_STR);
        $insertDb->bindValue(':img', $this->img, PDO::PARAM_STR);
        $insertDb->bindValue(':state', $this->state, PDO::PARAM_BOOL);
        $insertDb->bindValue(':id_category', $this->id_category, PDO::PARAM_INT);
        $insertDb->bindValue(':id_user', $this->id_user, PDO::PARAM_INT);
        return $insertDb->execute();
    }

    /**
     * Method  Qui permet de changer un article
     * @return bool
     */
    public function updateArticleById()
    {
        $query = 'UPDATE `article` SET  `title`=:title, `content`=:content , `img`=:img , `state`=:state, `modify_at`=:modify_at, `id_user`=:id_user, `id_category`=:id_category WHERE `id`=:id;';
        $findProfil = $this->db->prepare($query);
        $findProfil->bindValue(':title', $this->title, PDO::PARAM_STR);
        $findProfil->bindValue(':img', $this->img, PDO::PARAM_STR);
        $findProfil->bindValue(':content', $this->content, PDO::PARAM_STR);
        $findProfil->bindValue(':state', $this->state, PDO::PARAM_BOOL);
        $findProfil->bindValue(':modify_at', $this->modify_at, PDO::PARAM_STR);
        $findProfil->bindValue(':id_user', $this->id_user, PDO::PARAM_INT);
        $findProfil->bindValue(':id_category', $this->id_category, PDO::PARAM_INT);
        $findProfil->bindValue(':id', $this->id, PDO::PARAM_INT);
        return $findProfil->execute();
    }
    /**
     * Methode qui permet d'effacer un article
     */
    public function deleteArticleById()
    {
        $query = 'DELETE FROM `article` WHERE `id`=:id;';
        $article = $this->db->prepare($query);
        $article->bindValue(':id', $this->id, PDO::PARAM_INT);
        return $article->execute();
    }
    /**
     * Methode qui permet d'effacer les articles d'une category
     */
    public function deleteArticlesByIdCategory()
    {
        $query = 'DELETE FROM `article` WHERE `id_category`=:id_category;';
        $article = $this->db->prepare($query);
        $article->bindValue(':id_category', $this->id_category, PDO::PARAM_INT);
        return $article->execute();
    }
}
