<?php

namespace App\src\Entity;

use App\src\Entity\Database;
use PDO;

class User extends Database
{
    //Attribut
    public $id;
    public $email;
    public $createdAt;
    public $role;
    public $pass;
    public $token;

    /**
     * Method construct qui ce connecte a ma base de données
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Method qui retourne un TOKEN 
     * @return HASH
     */
    public function generateCSRFToken()
    {
        $length = 10;
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ%=';
        $character = strlen($characters);
        $token = '';
        for ($i = 0; $i < $length; $i++) {
            $token .= $characters[mt_rand(0, $character - 1)];
        }
        return $token;
    }

    /**
     * Method qui sert a inserer un nouveau user
     * @return requete INSERT
     */
    public function addUser()
    {
        $insert = 'INSERT INTO `user` (`email`, `password`, `token`) VALUES (:email, :pass, :token);';
        $insertDb = $this->db->prepare($insert);
        $insertDb->bindValue(':email', $this->email, PDO::PARAM_STR);
        $insertDb->bindValue(':pass', $this->pass, PDO::PARAM_STR);
        $insertDb->bindValue(':token', $this->token, PDO::PARAM_STR);
        return $insertDb->execute();
    }

    public function getUserList()
    {
        $query = 'SELECT * FROM `user`';
        $requete = $this->db->query($query);
        return $requete->fetchAll();
    }

    /**
     * Method qui permet de recuperer les infos d'un user via son mail
     */
    public function getUserMail()
    {
        $query = 'SELECT * FROM `user` WHERE `email`=:email;';
        $fetchProfil = $this->db->prepare($query);
        $fetchProfil->bindValue(':email', $this->email, PDO::PARAM_STR);
        $fetchProfil->execute();
        return $fetchProfil->fetch(PDO::FETCH_OBJ);
    }

    /**
     * method qui permet de modifier un token(jeton) de connexion
     */
    public function setTokenUser()
    {
        $query = 'UPDATE `user` SET `token`=:token WHERE `id`=:id;';
        $findProfil = $this->db->prepare($query);
        $findProfil->bindValue(':id', $this->id, PDO::PARAM_INT);
        $findProfil->bindValue(':token', $this->token, PDO::PARAM_STR);
        return $findProfil->execute();
    }

    /**
     * method qui permet de recuperer un token(jeton) de connexion
     */
    public function getTokenUser()
    {
        $req = 'SELECT `token` FROM `user` WHERE id=:id ';
        $prep = $this->db->prepare($req);
        $prep->bindValue(':id', $this->id, PDO::PARAM_INT);
        $prep->execute();
        $tokenId = $prep->fetch(PDO::FETCH_OBJ);
        return $tokenId->tokenUser;
    }

    /**
     * Method qui renvoi true si il y a des occurences d'email dans la table users
     */
    public function checkFreeMail()
    {
        $email = 'SELECT `email` FROM `user` WHERE `email`= :email;';
        $reqmail = $this->db->prepare($email);
        $reqmail->bindValue(':email', $this->email, PDO::PARAM_STR);
        $reqmail->execute();
        return $reqmail->rowCount();
    }
    /**
     * Methode qui permet de supprimer un utilisateur
     */
    public function removeUser(int $id)
    {
        $query = 'DELETE FROM `user` WHERE `id`=:id;';
        $article = $this->db->prepare($query);
        $article->bindValue(':id', $id, PDO::PARAM_INT);
        return $article->execute();
    }
}
