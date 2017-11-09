<?php

// Home page
$app->get('/', function () {
    require '../src/model.php';
    $articles = getArticles();

    ob_start();             // start buffering HTML output
    require '../views/view.php';
    $view = ob_get_clean(); // assign HTML output to $view
    return $view;
});


/**
 * Silex permet de définir des routes, c'est-à-dire des points d'entrée dans l'application.
 * À chaque route est associée une réponse construite par notre code.
 * La route ci-dessus correspond à l'URL racine de l'application (/).
 * La fonction anonyme associée à cette route utilise la fonction getArticles
 * définie dans le fichier model.php pour récupérer la liste des articles.
 *
 * Une fonction qui gère une route est appelée un contrôleur.
 */