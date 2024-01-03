<?php

namespace App\src\Controller;

use App\src\Entity\Article;
use App\src\Controller\TwigController;
use App\src\Entity\Category;
use App\src\Entity\Comment;
use DateTime;

class ArticleController extends TwigController
{
    public function index()
    {
        if ($_SESSION["role"] == "ROLE_ADMIN") {
            $article = new Article;
           // $full = $article->getArticleAuthorCategoryComment();
           // var_dump($full);
            $articles = $article->getArticles();
            $category = new Category();
            $categorys = $category->findAll();
            echo $this->twig->render("article/index.html.twig", [
                'articles' => $articles,
                'data' => 'Bienvenue sur le controller Article',
                'session' => $_SESSION,
                'categorys' => $categorys
            ]);
        } else {
            header("Location: /public/home");
        }
    }

    public function create()
    {
        $article = new Article();
        if (isset($_POST["btnCreate"])) {
            $formError = [];
            $article = new Article();
            if (isset($_POST["title"]) && !empty($_POST["title"])) {
                $article->title = htmlspecialchars($_POST["title"]);
            } else {
                $formError["title"] = "Veuillez entrer un titre";
            }
            if (isset($_POST["content"]) && !empty($_POST["content"])) {
                $article->content = htmlspecialchars($_POST["content"]);
            } else {
                $formError["content"] = "Veuillez entrer un contenu";
            }
            $id = $article->lastId()["id"] + 1;
            // Recupere mon image puis la redirige afin de la stocker dans mon dossier avatar
            if (isset($_FILES['file']) && !empty($_FILES['file']['name'])) {
                // var_dump($_FILES);
                // Initalisation d'une variable qui stockera la taille du poid autoriser pour l'avatar 2mo
                $tailleMax = 1000000; //octet = 1MO
                // Déclaration d'une variable qui stockera les extension autoriser pour l'avatar
                $extensionValid = array('png', 'jpg', 'jpeg');
                // Condition qui s'effectue si le poid de mon image est inferieur ou egal a ma limite autoriser
                if ($_FILES['file']['size'] <= $tailleMax) {
                    // Declaration d'une variable qui sotkera l'extension de mon image tout en la mettant en miniscule et on ignore le caractere . puis on recupere le nom de l'extension apres le caractere ignorer
                    $extensionUpload = strtolower(substr(strrchr($_FILES['file']['name'], '.'), 1));
                    // Condition qui verifie si l'extesion récuperer est egal a notre tableau d'extension
                    if (in_array($extensionUpload, $extensionValid)) {
                        // J'initialise une variable qui stocke le chemin que je definie ou sera stoker mon avatar
                        $way = str_replace(' ', '', 'album/article_' . $id . '.' . $extensionUpload);
                        // var_dump($way);
                        //Déclaration d'une variable qui stokera la valeur de retour du Deplacement d'un fichier téléchargé renvoi TRUE ou FALSE
                        $result =  move_uploaded_file($_FILES['file']['tmp_name'], ROOT . "/" . $way);
                        if ($result) {
                            $article->img = htmlspecialchars('../../' . $way);
                        } else {
                            $formError['file'] = 'Désoler une erreur c\'est produite , veuillez charger une autre image';
                        }
                    } else {
                        $formError['file'] = 'Votre format de photo ne correspond pas il doit être au format jpg, jpeg, gif ou png';
                    }
                } else {
                    $formError['file'] = 'Votre photo ne doit pas depasser 1 MO';
                }
            } else {
                $formError['file'] = 'Veuillez selectionner une image';
            }
            if (isset($_POST["state"]) && !empty($_POST["state"])) {
                $article->state = htmlspecialchars($_POST["state"]);
            } else {
                $article->state = false;
            }
            if (isset($_POST["category"]) && $_POST["category"] != 0) {
                $article->id_category = htmlspecialchars($_POST['category']);
            } else {
                $formError["category"] = "Veuillez choisir une categorie";
            }
            $article->id_user = $_SESSION['id'];
            if (!$formError) {
                $article->createArticle();
                header("Location: /public/article");
            }
        }
        if ($_SESSION["role"] == "ROLE_ADMIN") {
            $categories = new Category();
            $categorys = $categories->findAll();
           // var_dump($_SESSION);
            echo $this->twig->render("article/form.html.twig", [
                'data' => 'Bienvenue sur le controller Article/create/',
                'session' => $_SESSION,
                'error' => (isset($formError)) ? $formError : "",
                'count' => $article->lastId(),
                'categorys' => $categorys
            ]);
        } else {
            header("Location: /home ");
        }
    }

    public function view(int $params)
    {
        $getComment = new Comment();
        $getComment->setArticle($params);
        $comments = $getComment->getCommentByArticle();

        $getArticle = new Article;
        $getArticle->id = $params;
        $article = $getArticle->getArticleById();
        if ($getArticle) {
            echo $this->twig->render("article/index.html.twig", [
                'article' => $article,
                'data' => 'Bienvenue sur le controller Article/view',
                'session' => $_SESSION,
                'comments' => $comments
            ]);
        } else {
            echo $this->twig->render("article/index.html.twig", [
                'data' => 'Bienvenue sur le controller Article/view',
                'session' => $_SESSION,
            ]);
        }
    }

    public function modify(int $params)
    {
        $getArticle = new Article;
        $getArticle->id = $params;
        $article = $getArticle->getArticleById();
        $date = new DateTime();
        if (isset($_POST["btnUpdate"])) {
            $getArticle->id = $params;
            $getArticle->title = htmlspecialchars($_POST["title"]);
            $getArticle->content = htmlspecialchars($_POST["content"]);

            // Recupere mon image puis la redirige afin de la stocker dans mon dossier avatar
            if (isset($_FILES['file']) && !empty($_FILES['file']['name'])) {
                //var_dump($_FILES);
                // Initalisation d'une variable qui stockera la taille du poid autoriser pour l'avatar 2mo
                $tailleMax = 1000000; //octet = 1MO
                // Déclaration d'une variable qui stockera les extension autoriser pour l'avatar
                $extensionValid = array('png');
                // Condition qui s'effectue si le poid de mon image est inferieur ou egal a ma limite autoriser
                if ($_FILES['file']['size'] <= $tailleMax) {
                    // Declaration d'une variable qui sotkera l'extension de mon image tout en la mettant en miniscule et on ignore le caractere . puis on recupere le nom de l'extension apres le caractere ignorer
                    $extensionUpload = strtolower(substr(strrchr($_FILES['file']['name'], '.'), 1));
                    // Condition qui verifie si l'extesion récuperer est egal a notre tableau d'extension
                    if (in_array($extensionUpload, $extensionValid)) {
                        // J'initialise une variable qui stocke le chemin que je definie ou sera stoker mon avatar
                        $way = str_replace(' ', '', 'album/article_' . $params . '.' . $extensionUpload);
                        var_dump($way);
                        //Déclaration d'une variable qui stokera la valeur de retour du Deplacement d'un fichier téléchargé renvoi TRUE ou FALSE
                        $result =  move_uploaded_file($_FILES['file']['tmp_name'], ROOT . "/" . $way);
                        if ($result) {
                            $getArticle->img = '../../' . $way;
                        } else {
                            $formError['file'] = 'Désoler une erreur c\'est produite , veuillez charger une autre image';
                        }
                    } else {
                        $formError['file'] = 'votre format de photo ne correspond pas il doit être au format png';
                    }
                } else {
                    $formError['file'] = 'votre photo ne doit pas depasser 1 mo';
                }
            } else {
                $getArticle->img = '../../album/article_1.png';
            }
            $getArticle->state = htmlspecialchars($_POST["state"]);
            $getArticle->modify_at = $date->format('d/m/Y à H:i:s');
            if (isset($_POST["category"])) {
                $getArticle->id_category = htmlspecialchars($_POST['category']);
            } else {
                $formError['category'] = 'Veuillez choir une categorie';
            }
            $getArticle->id_user = $_SESSION['id'];
            if ($formError <= 0) {
                $getArticle->updateArticleById();
                header("Location: /public/article");
            }
        }
        //Limite l'accés au controller à l'admin
        if ($_SESSION["role"] == "ROLE_ADMIN") {
            $categories = new Category();
            $categorys = $categories->findAll();
            echo $this->twig->render("article/form.html.twig", [
                'article' => $article,
                'data' => 'Bienvenue sur le controller Article/modify/',
                'session' => $_SESSION,
                'categorys' => $categorys
            ]);
        } else {
            header("Location: /public/home");
        }
    }

    public function delete(int $params)
    {
        //Limite l'accés au controller à l'admin
        if ($_SESSION["role"] == "ROLE_ADMIN") {
            $getArticle = new Article;
            $getArticle->id = $params;
            $article = $getArticle->getArticleById();
            unlink(ROOT . strchr($article->img, "album"));
            $getArticle->deleteArticleById();
            header("Location: /public/article");
        } else {
            header("Location: /public/home");
        }
    }
}
