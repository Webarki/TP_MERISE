<?php

namespace App\src;

use App\src\Controller\TwigController;

/**
 * Class Noyau Routeur 
 */
class Kernel extends TwigController
{
    public function start()
    {
        if (isset($_COOKIE["PHPSESSID"])) {
            session_start();
        }
        //var_dump($_SERVER);
        $params = [];
        $params = explode("/", $_SERVER["PATH_INFO"]);
        // var_dump($params);
        if (isset($params[1]) && !empty($params[1]) && $params[1] != $params[1] . '/ ') {
            // var_dump($controller);
            $controller = "\\App\\src\\Controller\\" . ucfirst($params[1]) . "Controller";
            //var_dump($method);
            $method = (isset($params[2])) ? $params[2] :  "index";
            if (isset($params[2]) && empty($params[3])) {
               // header("Location: /public/home");
            } else if (isset($params[1]) && "/" . $params[1] == $_SERVER["PATH_INFO"]) {
                $data = (isset($params[3])) ? $params[3] :  "";
            } else if (isset($params[3]) && !empty($params[3]) && intval($params[3]) == $params[3]) {
                $data = (isset($params[3])) ? $params[3] :  "";
            } else {
                header("Location:/public/home");
                // http_response_code(404);
                // echo "La donné n'est pas integer";
            }
            //var_dump($data);
            $controllers = new $controller();
            if (method_exists($controllers, $method)) {
                $fh = fopen('../tmp/logs.txt', 'a');
                fwrite($fh, $_SERVER['REMOTE_ADDR'] . ' ' . $_SERVER['REQUEST_METHOD'] . ' ' . $_SERVER['REQUEST_URI'] . ' ' . date('c') . "\n");
                fclose($fh);
                (isset($data)) ? $controllers->$method($data) : $controllers->$method();
            } else {
                header("Location:/public/home");
                //http_response_code(404);
                //echo "Aucune methode existe";
            }
        } else {
            header('Location: /public/home');
        }
    }
}
