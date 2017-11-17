<?php

use Symfony\Component\HttpFoundation\Request;
use silex_openC\src\Domain\Comment;
use silex_openC\Form\Type\CommentType;

// Home page
$app->get('/', function () use ($app) {
    $articles = $app['dao.article']->findAll();
    return $app['twig']->render('index.html.twig', array('articles' => $articles));
})->bind('home');

// Article details with comments
/* $app->get('/article/{id}', function ($id) use ($app) {
  $article = $app['dao.article']->find($id);
  $comments = $app['dao.comment']->findAllByArticle($id);
  return $app['twig']->render('article.html.twig', [
  'article' => $article,
  'comments' => $comments
  ]);
  })->bind('article'); */
// le contrôleur de la route /article/{id}, $app->match, gère l'accès à cette route via les commandes GET et POST
$app->match('/article/{id}', function($id, Request $request) use ($app) {
    $article = $app['dao.article']->find($id);
    $commentFormView = null;

    if ($app['security.authorization_checker']->isGranted('IS_AUTHENTICATED_FULLY')) {
        // A user is fully authenticated => he can add comments
        $comment = new Comment();
        $comment->setArticle($article);
        // on récupère l'utilisateur en cours
        $user = $app['user'];
        $comment->setAuthor($user);
        // on créé un nouveau commentaire et son formulaire
        $commentForm = $app['form.factory']->create(CommentType::class, $comment);
        // on soumet le formulaire avec la méthode handleRequest()
        $commentForm->handleRequest($request);
        // si un commentaire est posté et est valide on insère en BDD
        if ($commentForm->isSubmitted() && $commentForm->isValid()) {
            // on fait appel au DAO pour sauvegarder le nouveau commentaire
            $app['dao.comment']->save($comment);
            // on créé un message de succès
            $app['session']->getFlashBag()->add('success', 'Votre commentaire a bien été ajouté.');
        }
        $commentFormView = $commentForm->createView();
    }
    // on récupère tous les commentaires de l'article en cours
    $comments = $app['dao.comment']->findAllByArticle($id);

    return $app['twig']->render('article.html.twig', [
                'article' => $article,
                'comments' => $comments,
                'commentForm' => $commentFormView
    ]);
})->bind('article');

// Login form
// la classe Symfony 'Request' permet d'afficher la vue login.html.twig en lui passant en paramètres l'éventuelle dernière erreur de sécurité (ex user non reconnu) et le dernier user utilisé
// /!\ cette nouvelle route est nommée 'login' grâce à l'appel de la méthode 'bind', sans cela l'appel à la fonction 'path('login') dans un template twig génèrait une erreur
$app->get('/login', function(Request $request) use ($app) {
    return $app['twig']->render('login.html.twig', [
                'error' => $app['security.last_error']($request),
                'last_username' => $app['session']->get('_security.last_username'),
    ]);
})->bind('login');

// Silex permet de définir des routes, c'est-à-dire des points d'entrée dans l'application. À chaque route est associée une réponse construite par notre code. La route ci-dessus correspond à l'URL racine de l'application (/).La fonction anonyme associée à cette route utilise la fonction getArticles définie dans le fichier model.php pour récupérer la liste des articles.Une fonction qui gère une route est appelée un contrôleur.
// L'appel à la fonction getArticles est remplacé par l'utilisation du service dao.article enregistré dans app/app.php. L'appel à $app['dao.article'] renvoie un objet de la classe ArticleDAO dont on utilise ensuite la méthodefindAll pour récupérer la liste des articles.
// Ce contrôleur a maintenant besoin de l'objet application Silex $app, d'où l'ajout du use($app) dans sa définition.
// on demande au service Twig ($app['twig'] ) de générer le template index.html.twig en lui passant ses données dynamiques en paramètre.
// Ici, la seule donnée dynamique est une variable nommée articles qui contient le tableau d'objets de la classe Article renvoyé par la partie Modèle
// Route pour la partie Admin du site
// Admin home page
$app->get('/admin', function() use ($app) {
    $articles = $app['dao.article']->findAll();
    $comments = $app['dao.comment']->findAll();
    $users = $app['dao.user']->findAll();
    return $app['twig']->render('admin.html.twig', [
                'articles' => $articles,
                'comments' => $comments,
                'users' => $users
    ]);
})->bind('admin');
// Le contrôleur associé génère la vue admin.html.twig en lui fournissant les listes des articles, des commentaires et des utilisateurs. La méthode findAll existe déjà dans la classe ArticleDAO. Il faut créer les deux autres CommentDAO et UserDAO.