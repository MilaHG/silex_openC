<?php

// rajout par rapport au code OpenC
// rajout par rapport au code OpenC
// rajout par rapport au code OpenC

use Silex\Provider\AssetServiceProvider;
use Silex\Provider\DoctrineServiceProvider;
use Silex\Provider\FormServiceProvider;
use Silex\Provider\LocaleServiceProvider;
use Silex\Provider\SecurityServiceProvider;
use Silex\Provider\SessionServiceProvider;
use Silex\Provider\TranslationServiceProvider;
use Silex\Provider\TwigServiceProvider;
use silex_openC\src\DAO\ArticleDAO;
use silex_openC\src\DAO\CommentDAO;
use silex_openC\src\DAO\UserDAO;
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
]); // les assets pour les paths assets('home')
// paramétrages de sécurité
// SessionServiceProvider démarre automatiquement la gestion des sessions PHP
$app->register(new SessionServiceProvider());
// paramétrage du firewall associé au service SecurityServiceProvider :
// pattern => partie sécurisée de l'application sous forme d'expression régulière (avec '^/' on sécurise tout le site)
// anonymous => un utilisateur même non authentifié peut accéder à la partie sécurisée => c'est nécessaire pour que les visiteurs anonymes puissent toujours consulter les articles
// logout => permet aux utilisateurs connectés de se déconnecter
// form => permet d'utiliser un formulaire comme méthode d'authentification
// login_path => chemin vers le formulaire d'authentification
// check_path => formulaire d'authentification
// users => fourisseur de données utilisateur => instance de la classe UserDAO
$app->register(new SecurityServiceProvider(), [
    'security.firewalls' => [
        'secured' => [
            'pattern' => '^/',
            'anonymous' => true,
            'logout' => true,
            'form' => [
                'login_path' => '/login',
                'check_path' => '/login_check'],
            'users' => function () use ($app) {
                return new UserDAO($app['db']);
            },
        ],
    ],
    // accès back-office role admin avec une hiérarchie => si on a le role admin on a forcément le role user
    'security.role_hierarchy' => [
        'ROLE_ADMIN' => ['ROLE_USER'],
    ],
    'security.access_rules' => [
        ['^/admin', 'ROLE_ADMIN'],
    ],
]);
// Forms
// Le composant form regroupe les services de gestion des formulaires.
// Le composant translation offre des services de traduction nécessaires pour utiliser le composant form.
// Le composant config est également nécessaire au bon fonctionnement de form.
$app->register(new FormServiceProvider());
$app->register(new LocaleServiceProvider());
$app->register(new TranslationServiceProvider());

// Register services for the articles
$app['dao.article'] = function ($app) {
    return new ArticleDAO($app['db']);
};
// Register services for the users
$app['dao.user'] = function ($app) {
    return new UserDAO($app['db']);
};
// Register services for the comments
$app['dao.comment'] = function ($app) {
    $commentDAO = new CommentDAO($app['db']);
    $commentDAO->setArticleDAO($app['dao.article']);
    $commentDAO->setUserDAO($app['dao.user']);
    return $commentDAO;
};
