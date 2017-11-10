<?php

use Symfony\Component\Debug\ErrorHandler;
use Symfony\Component\Debug\ExceptionHandler;

/*
 * Paramétrage de l'application
 */

// Register global error and exception handlers
ErrorHandler::register();
ExceptionHandler::register();

// Register service providers.
$app->register(new Silex\Provider\DoctrineServiceProvider()); // Doctrine
$app->register(new Silex\Provider\TwigServiceProvider(), ['twig.path' => __DIR__ . '/../views',
]); // Twig
/*
 * Twig est configuré pour que le répertoire
 * dans lequel nous stockerons nos templates
 * soit le répertoire views
 * Le service $app['twig'] est défini
 * automatiquement lors de l'enregistrement
 * du fournisseur TwigServiceProvider.
 */
$app->register(new Silex\Provider\AssetServiceProvider(), ['assets.version' => 'v1'
]);
// Register services.
$app['dao.article'] = function ($app) {
    return new silex_openC\src\DAO\ArticleDAO($app['db']);
};
