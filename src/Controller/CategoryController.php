<?php

namespace App\src\Controller;

use App\src\Entity\Article;
use App\src\Controller\TwigController;
use App\src\Entity\Category;

class CategoryController extends TwigController
{
    public function index()
    {
        $category = new Category();
        $categories = $category->findAll();
        if ($_SESSION["role"] == "ROLE_ADMIN") {
            echo $this->twig->render("category/index.html.twig", [
                'session' => $_SESSION,
                'categories' => $categories,
                'data' => 'Bienvenue sur le controller Category'
            ]);
        } else {
            header("Location: /public/login");
        }
    }

    public function create()
    {
        if (isset($_POST["btnCreate"])) {
            $category = new Category();
            $category->setTitle(htmlspecialchars($_POST["title"]));
            $category->setDescription(htmlspecialchars($_POST["description"]));
            $category->createCategory();
            header("Location: /public/category");
        }
        if ($_SESSION["role"] == "ROLE_ADMIN") {
            echo $this->twig->render("category/form.html.twig", [
                'session' => $_SESSION,
                'data' => 'Bienvenue sur le controller Category'
            ]);
        } else {
            header("Location: /public/login");
        }
    }

    public function modify(int $params)
    {
        $category = new Category();
        $categorie = $category->find($params);
        if (isset($_POST["btnUpdate"])) {
            $category->setTitle(htmlspecialchars($_POST["title"]));
            $category->setDescription(htmlspecialchars($_POST["description"]));
            $category->setCategory($params);
            header("Location: /public/category");
        }
        if ($_SESSION["role"] == "ROLE_ADMIN") {
            echo $this->twig->render("category/form.html.twig", [
                'session' => $_SESSION,
                'data' => 'Bienvenue sur le controller Category',
                'categorie' => $categorie
            ]);
        } else {
            header("Location: /public/login");
        }
    }

    public function remove(int $params)
    {
        if ($_SESSION["role"] == "ROLE_ADMIN") {
            $getArticles = new Article();
            $getArticles->id_category = $params;
            $articles = $getArticles->getArticleListByCategory();
            $category = new Category();
            if ($articles) {
                $getArticles->deleteArticlesByIdCategory($params);
                $category->removeCategory($params);
                header("Location: /public/category");
            } else {
                $category->removeCategory($params);
                header("Location: /public/category");
            }
        } else {
            header("Location: /public/login");
        }
    }
}
