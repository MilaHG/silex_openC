<?php

use silex_openC\Form\Type\ArticleType;
use silex_openC\Form\Type\CommentType;
use silex_openC\Form\Type\UserType;
use silex_openC\src\Domain\Article;
use silex_openC\src\Domain\Comment;
use Symfony\Component\HttpFoundation\Request;
use silex_openC\src\Domain\User;

// Home page
$app->get('/', function () use ($app) {
    $articles = $app['dao.article']->findAll();
    return $app['twig']->render('index.html.twig', array('articles' => $articles));
})->bind('home');

// Article details with comments
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
//
// Admin - ajout nouvel article
$app->match('/admin/article/add', function(Request $request) use ($app) {
    $article = new Article();
    $articleForm = $app['form.factory']->create(ArticleType::class, $article);
    $articleForm->handleRequest($request);
    if ($articleForm->isSubmitted() && $articleForm->isValid()) {
        $app['dao.article']->save($article);
        $app['session']->getFlashBag()->add('success', 'L\'article a été ajouté.');
    }
    return $app['twig']->render('article_form.html.twig', [
                'title' => 'Nouvel article',
                'articleForm' => $articleForm->createView()
    ]);
})->bind('admin_article_add');

// Admin - modification article
$app->match('/admin/article/{id}/edit', function($id, Request $request) use ($app) {
    $article = $app['dao.article']->find($id);
    $articleForm = $app['form.factory']->create(ArticleType::class, $article);
    $articleForm->handleRequest($request);
    if ($articleForm->isSubmitted() && $articleForm->isValid()) {
        $app['dao.article']->save($article);
        $app['session']->getFlashBag()->add('success', 'L\'article a bien été mis à jour.');
    }
    return $app['twig']->render('article_form.html.twig', [
                'title' => 'Modifier un article',
                'articleForm' => $articleForm->createView()
    ]);
})->bind('admin_article_edit');


// Admin - suppression article
$app->get('/admin/article/{id}/delete', function($id, Request $request) use ($app) {
    // Suppression de tous les commentaires attachés
    $app['dao.comment']->deleteAllByArticle($id);
    // Suppression de l'article
    $app['dao.article']->delete($id);
    $app['session']->getFlashBag()->add('success', 'L\'article et ses commentaires ont été supprimés.');
    // Redirection sur la page d'accueil
    return $app->redirect($app['url_generator']->generate('admin'));
})->bind('admin_article_delete');

// Admin - Modifier un commentaire
$app->match('/admin/comment/{id}/edit', function($id, Request $request) use ($app) {
    $comment = $app['dao.comment']->find($id);
    $commentForm = $app['form.factory']->create(CommentType::class, $comment);
    $commentForm->handleRequest($request);
    if ($commentForm->isSubmitted() && $commentForm->isValid()) {
        $app['dao.comment']->save($comment);
        $app['session']->getFlashBag()->add('success', 'Commentaire mis à jour.');
    }
    return $app['twig']->render('comment_form.html.twig', [
                'title' => 'Modifier le commentaire',
                'commentForm' => $commentForm->createView()
    ]);
})->bind('admin_comment_edit');

// Admin - Supprimer un commentaire
$app->get('/admin/comment/{id}/delete', function($id, Request $request) use ($app) {
    $app['dao.comment']->delete($id);
    $app['session']->getFlashBag()->add('success', 'Le commentaire a été supprimé.');
    // Redirection sur la page d'accueil
    return $app->redirect($app['url_generator']->generate('admin'));
})->bind('admin_comment_delete');

/*
 * contrôleurs pour ajout/modif/supp d'un utilisateur
 *
 * Fichiers liés :
 * form/UserType.php => formulaire associé à un utilisateur
 * views/user_form.html.twig => affiche les champs du formulaire UserType.php
 * views/admin.html.twig => formulaire d'affichage pour la gestion
 * des utilisateurs - Partie ADMIN
 * src/DAO/UserDAO.php => Méthodes de modification et de suppression d'un utilisateur
 * src/DAO/CommentDAO.php => supp tous commentaires liés à 1 utilisateur
 */
// Ajouter un user
$app->match('/admin/user/add', function(Request $request) use ($app) {
    $user = new User();
    $userForm = $app['form.factory']->create(UserType::class, $user);
    $userForm->handleRequest($request);
    if ($userForm->isSubmitted() && $userForm->isValid()) {
        // génère un code aléatoire pour le salt
        $salt = substr(md5(time()), 0, 23);
        $user->setSalt($salt);
        $plainPassword = $user->getPassword();
        // service Symfony de hashage du MDP
        $encoder = $app['security.encoder.bcrypt'];
        // compiler le MDP encodé
        $password = $encoder->encodePassword($plainPassword, $user->getSalt());
        $user->setPassword($password);
        $app['dao.user']->save($user);
        $app['session']->getFlashBag()->add('success', 'Votre compte utilisateur a été créé.');
    }
    return $app['twig']->render('user_form.html.twig', [
                'title' => 'Nouvel utilisateur',
                'userForm' => $userForm->createView()
    ]);
})->bind('admin_user_add');

// Modifier un user
$app->match('/admin/user/{id}/edit', function($id, Request $request) use ($app) {
    $user = $app['dao.user']->find($id);
    $userForm = $app['form.factory']->create(UserType::class, $user);
    $userForm->handleRequest($request);
    if ($userForm->isSubmitted() && $userForm->isValid()) {
        $plainPassword = $user->getPassword();
        // service Symfony de hashage du MDP
        $encoder = $app['security.encoder_factory']->getEncoder($user);
        // compiler le MDP encodé avec le salt et l'attribuer au user
        $password = $encoder->encodePassword($plainPassword, $user->getSalt());
        $user->setPassword($password);
        $app['dao.user']->save($user);
        $app['session']->getFlashBag()->add('success', 'Les informations de votre compte ont été mises à jour.');
    }
    return $app['twig']->render('user_form.html.twig', [
                'title' => 'Modifier le compte utilisateur',
                'userForm' => $userForm->createView()
    ]);
})->bind('admin_user_edit');

// Supprimer un user
$app->get('/admin/user/{id}/delete', function ($id, Request $request) use ($app) {
    // supprimer tous les commentaires associés
    $app['dao.comment']->deleteAllByUser($id);
    // supprimer le user
    $app['dao.user']->delete($id);
    $app['session']->getFlashBag()->add('success', 'Le compte utilisateur a bien été supprimé');
    // rediriger sur la page d'accueil
    return $app->redirect($app['url_generator']->generate('admin'));
})->bind('admin_user_delete');
