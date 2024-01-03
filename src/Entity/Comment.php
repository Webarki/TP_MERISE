<?php

namespace App\src\Entity;

use PDO;

class Comment extends Database
{
    private $id;
    private $username;
    private $content;
    private $state;
    private $created_at;
    private $id_user;
    private $id_article;

    public function __construct()
    {
        parent::__construct();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function setUsername($username)
    {
        $this->username = $username;
        return $this->username;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function setContent($content)
    {
        $this->content = $content;
        return $this->content;
    }

    public function getState()
    {
        return $this->state;
    }

    public function setState($state)
    {
        $this->state = $state;
        return $this->state;
    }

    public function getCreatedAt()
    {
        return $this->created_at;
    }

    public function getUser()
    {
        return $this->id_user;
    }

    public function setUser($id_user)
    {
        $this->id_user = $id_user;
        return $this->id_user;
    }

    public function getArticle()
    {
        return $this->id_article;
    }

    public function setArticle($id_article)
    {
        $this->id_article = $id_article;
        return $this->id_article;
    }

    /**
     * methode permettant de recuperer la liste des commentaires
     */
    public function getCommentList()
    {
        $query = 'SELECT `id`,`username`,`content`, `state`,DATE_FORMAT(`created_at`,\'%e/%m/%Y\') AS `createdAt`, `id_user` , `id_category` FROM `comment`';
        $queryResult = $this->db->query($query);
        return $queryResult->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * methode permettant de recuperer les commentaires d'un article
     */
    public function getCommentByArticle()
    {
        $query = 'SELECT * FROM `comment` WHERE `id_article`=:id_article';
        $findProfil = $this->db->prepare($query);
        $findProfil->bindValue(':id_article', $this->id_article, PDO::PARAM_INT);
        $findProfil->execute();
        return $findProfil->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * methode permettant de recuperer les commentaires d'un user
     */
    public function getCommentByUser()
    {
        $query = 'SELECT * FROM `comment` WHERE `id_user`=:id_user';
        $findProfil = $this->db->prepare($query);
        $findProfil->bindValue(':id_user', $this->id_user, PDO::PARAM_INT);
        $findProfil->execute();
        return $findProfil->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * methode permettant de recuperer la liste des commentaires
     */
    public function findAll()
    {
        $query = 'SELECT * FROM `comment`';
        $queryResult = $this->db->query($query);
        return $queryResult->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * methode permettant de recuperer les commentaires dont le statut est true
     */
    public function getCommentListStateTrue()
    {
        $query = 'SELECT * FROM `comment` WHERE `state`=1';
        $queryResult = $this->db->query($query);
        return $queryResult->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Methode qui permet de recuperer un commentaire par son id
     */
    public function find($id)
    {
        $requete = 'SELECT * FROM `comment` WHERE `id`=:id ;';
        $find = $this->db->prepare($requete);
        $find->bindValue(':id', $id, PDO::PARAM_INT);
        if ($find->execute()) {
            return $find->fetch(PDO::FETCH_OBJ);
        }
    }

    public function createComment()
    {
        $insert = 'INSERT INTO `comment` (`username`, `content`, `state`, `id_user`, `id_article`) VALUES (:username, :content, :state, :id_user, :id_article);';
        $insertDb = $this->db->prepare($insert);
        $insertDb->bindValue(':content', $this->content, PDO::PARAM_STR);
        $insertDb->bindValue(':username', $this->username, PDO::PARAM_STR);
        $insertDb->bindValue(':state', $this->state, PDO::PARAM_BOOL);
        $insertDb->bindValue(':id_user', $this->id_user, PDO::PARAM_INT);
        $insertDb->bindValue(':id_article', $this->id_article, PDO::PARAM_INT);
        return $insertDb->execute();
    }

    public function setComment(int $id)
    {
        $query = 'UPDATE `comment` SET `username`=:username, `content`=:content, `state`=:state, id_user=:id_user, id_article=:id_article WHERE `id`=:id;';
        $insertDb = $this->db->prepare($query);
        $insertDb->bindValue(':content', $this->content, PDO::PARAM_STR);
        $insertDb->bindValue(':username', $this->username, PDO::PARAM_STR);
        $insertDb->bindValue(':state', $this->state, PDO::PARAM_BOOL);
        $insertDb->bindValue(':id_user', $this->id_user, PDO::PARAM_INT);
        $insertDb->bindValue(':id_article', $this->id_article, PDO::PARAM_INT);
        $insertDb->bindValue(':id', $id, PDO::PARAM_INT);
        return $insertDb->execute();
    }

    /**
     * Methode qui permet d'effacer un commentaire
     */
    public function removeComment(int $id)
    {
        $query = 'DELETE FROM `comment` WHERE `id`=:id;';
        $article = $this->db->prepare($query);
        $article->bindValue(':id', $id, PDO::PARAM_INT);
        return $article->execute();
    }
}
