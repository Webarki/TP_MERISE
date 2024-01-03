<?php

namespace App\src\Controller;

use App\src\Entity\Article;
use App\src\Controller\TwigController;
use App\src\Entity\Category;
use App\src\Entity\Comment;

class CommentController extends TwigController
{
    public function index()
    {
        $comment = new Comment();
        $comments = $comment->findAll();
        if ($_SESSION["role"] == "ROLE_ADMIN") {
            echo $this->twig->render("comment/index.html.twig", [
                'session' => $_SESSION,
                'comments' => $comments,
                'data' => 'Bienvenue sur le controller Comment'
            ]);
        } else {
            header("Location: /public/login");
        }
    }
    public function create(int $params)
    {
        if (isset($_POST["btnCreate"])) {
            $comment = new Comment();
            $comment->setUsername($_SESSION["email"]);
            $comment->setContent($_POST["content"]);
            $comment->setState(false);
            $comment->setUser($_SESSION["id"]);
            $comment->setArticle($params);
            $comment->createComment();
            if ($_SESSION["role"] == "ROLE_ADMIN") {
                header("Location: /public/comment");
            } else {
                header("Location: /public/article/view/" . $comment->id_article);
            }
        }
        echo $this->twig->render("comment/form.html.twig", [
            'session' => $_SESSION,
            'data' => 'Bienvenue sur le controller Category',
            'params' => $params
        ]);
    }

    public function modify(int $params)
    {
        $comments = new Comment();
        $comment = $comments->find($params);
        if (isset($_POST["btnUpdate"])) {
            $comments->setUsername($comment->username);
            $comments->setContent($comment->content);
            $comments->setState($_POST["state"]);
            $comments->setUser($comment->id_user);
            $comments->setArticle($comment->id_article);
            $comments->setComment($params);
            header("Location: /public/comment");
        }
        if ($_SESSION["role"] == "ROLE_ADMIN") {
            echo $this->twig->render("comment/form.html.twig", [
                'session' => $_SESSION,
                'data' => 'Bienvenue sur le controller Category',
                'comment' => $comment
            ]);
        } else {
            header("Location: /public/login");
        }
    }

    public function remove(int $params)
    {
        $comment = new Comment();
        $comment->removeComment($params);
        if ($_SESSION["role"] == "ROLE_USER") {
            header("Location: /public/user/comment/" . $_SESSION['id']);
        } else if ($_SESSION["role"] == "ROLE_ADMIN") {
            header("Location: /public/comment");
        } else {
            header("Location: /public/login");
        }
    }
}
