<?php

/*
 * Ce fichier contient les options de configuration
 * liées au développement de notre application.
 *
 * Ce fichier inclut la configuration de production
 * puis paramètre Silex pour afficher des informations
 * de débogage détaillées en cas d'erreur (phase de
 * développement)
 */

// include the prod configuration
require __DIR__ . '/prod.php';

// enable the debug mode
$app['debug'] = true;

/*
 * Pour les tests avec PHPUNIT
 * Doctrine (db)
 */
// Doctrine (db)
$app['db.options'] = array(
    'driver' => 'pdo_mysql',
    'charset' => 'utf8',
    'host' => '127.0.0.1', // Obligatoire pour les tests PHPUnit au lieu de localhost
    'port' => '3306',
    'dbname' => 'microcms',
    'user' => 'microcms_user',
    'password' => 'secret',
);
