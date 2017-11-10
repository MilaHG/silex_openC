<?php

// Home page
$app->get('/', function () use ($app) {
$articles = $app['dao.article']->findAll();
return $app['twig']->render('index.html.twig', ['articles' => $articles])->bind('home');


// Article details with comments
$app->get('/article/{id}', function ($id) use ($app) {
$article = $app['dao.article']->find($id);
$comments = $app['dao.comment']->findAllByArticle($id);
return $app['twig']->render('article.html.twig', ['article' => $article, 'comments' => $comments]);
})->bind('article');

// Silex permet de définir des routes, c'est-à-dire des points d'entrée dans l'application. À chaque route est associée une réponse construite par notre code. La route ci-dessus correspond à l'URL racine de l'application (/).La fonction anonyme associée à cette route utilise la fonction getArticles définie dans le fichier model.php pour récupérer la liste des articles.Une fonction qui gère une route est appelée un contrôleur.
// L'appel à la fonction getArticles est remplacé par l'utilisation du service dao.article enregistré dans app/app.php. L'appel à $app['dao.article'] renvoie un objet de la classe ArticleDAO dont on utilise ensuite la méthodefindAll pour récupérer la liste des articles.
// Ce contrôleur a maintenant besoin de l'objet application Silex $app, d'où l'ajout du use($app) dans sa définition.
// on demande au service Twig ($app['twig'] ) de générer le template index.html.twig en lui passant ses données dynamiques en paramètre.
// Ici, la seule donnée dynamique est une variable nommée articles qui contient le tableau d'objets de la classe Article renvoyé par la partie Modèle