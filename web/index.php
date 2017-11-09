<?php

require_once __DIR__ . '/../vendor/autoload.php';

$app = new Silex\Application();

// enable the debug mode
$app['debug'] = true;

require __DIR__ . '/../app/routes.php';

$app->run();

/**
 * Ce fichier constitue le contrôleur frontal de notre application web.
 * Il centralise la gestion des requêtes HTTP entrantes.
 * Dans ce fichier:
 * - on instancie l'objet Silex principal $app,
 * - on active les informations de débogage
 * - puis on inclut la définition des routes de l'application (fichierroutes.php).‌
 */