<?php

require_once __DIR__ . '/../vendor/autoload.php';

$app = new Silex\Application();

// enable the debug mode
//$app['debug'] = true;

require __DIR__ . '/../app/config/dev.php';
require __DIR__ . '/../app/app.php';
require __DIR__ . '/../app/routes.php';
require __DIR__ . '/../src/DAO/DAO.php';
require __DIR__ . '/../src/DAO/ArticleDAO.php'; //rajout par rapport à version OpenC
require __DIR__ . '/../src/Domain/Article.php'; //rajout par rapport à version OpenC
require __DIR__ . '/../src/DAO/CommentDAO.php'; //rajout par rapport à version OpenC
require __DIR__ . '/../src/Domain/Comment.php'; //rajout par rapport à version OpenC
require __DIR__ . '/../src/DAO/UserDAO.php'; //rajout par rapport à version OpenC
require __DIR__ . '/../src/Domain/User.php'; //rajout par rapport à version OpenC
require __DIR__ . '/../src/Form/Type/CommentType.php'; //rajout par rapport à version OpenC
require __DIR__ . '/../src/Form/Type/ArticleType.php'; // rajout par rapport au code OpenC
require __DIR__ . '/../src/Form/Type/UserType.php'; // rajout par rapport au code OpenC


$app->run();

/**
 * Ce fichier constitue le contrôleur frontal de notre application web.
 * Il centralise la gestion des requêtes HTTP entrantes.
 * Dans ce fichier:
 * - on instancie l'objet Silex principal $app,
 * - on active les informations de débogage
 * - puis on inclut la définition des routes de l'application (fichier routes.php).‌
 */