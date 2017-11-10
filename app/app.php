<?php

use Silex\Provider\AssetServiceProvider;
use Silex\Provider\DoctrineServiceProvider;
use Silex\Provider\TwigServiceProvider;
use silex_openC\src\DAO\ArticleDAO;
use silex_openC\src\DAO\CommentDAO;
use Symfony\Component\Debug\ErrorHandler;
use Symfony\Component\Debug\ExceptionHandler;

/*
 * Paramétrage de l'application
 */

// Register global error and exception handlers
ErrorHandler::register();
ExceptionHandler::register();

// Register service providers.
$app->register(new DoctrineServiceProvider()); // Doctrine
$app->register(new TwigServiceProvider(), ['twig.path' => __DIR__ . '/../views',
]); // Twig
/*
 * Twig est configuré pour que le répertoire
 * dans lequel nous stockerons nos templates
 * soit le répertoire views
 * Le service $app['twig'] est défini
 * automatiquement lors de l'enregistrement
 * du fournisseur TwigServiceProvider.
 */
$app->register(new AssetServiceProvider(), ['assets.version' => 'v1'
]);
// Register services for the articles
$app['dao.article'] = function ($app) {
    return new ArticleDAO($app['db']);
};
// Register services for the comments
$app['dao.comment'] = function ($app) {
    $commentDAO = new CommentDAO($app['db']);
    $commentDAO->setArticleDAO($app['dao.article']);
    return $commentDAO;
};
