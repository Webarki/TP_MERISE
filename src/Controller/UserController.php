<?php

namespace App\src\Controller;

use App\src\Entity\Comment;
use App\src\Entity\User;

class UserController extends TwigController
{
    public function index()
    {
        if ($_SESSION['role'] == "ROLE_ADMIN") {
            $user = new User();
            $users = $user->getUserList();
            $getComment = new Comment();
            echo $this->twig->render('user/index.html.twig', [
                'data' => 'Bienvenue sur le controller User!',
                'users' => $users,
                'session' => $_SESSION
            ]);
        } else {
            header("Location: /public/home");
        }
    }

    public function comment(int $params)
    {
        if ($_SESSION) {
            $getComment = new Comment();
            $getComment->setUser($params);
            $comments = $getComment->getCommentByUser();
            echo $this->twig->render('user/comment.html.twig', [
                'data' => 'Bienvenue sur le controller User/Comment!',
                'comments' => $comments,
                'session' => $_SESSION
            ]);
        } else {
            header("Location: /public/login");
        }
    }

    public function remove(int $params)
    {
        if ($_SESSION["role"] == "ROLE_ADMIN") {
            $user = new User();
            $user->removeUser($params);
            header("Location: /public/user");
        } else {
            header("Location: /public/login");
        }
    }
}
