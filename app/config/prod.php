<?php

/*
 * Ce fichier contient les options de configuration de la
 * connexion à la BDD via DBAL(Doctrine)
 * liés à la mise en production de l'application
 */

// Doctrine (db)
$app['db.options'] = array(
    'driver' => 'pdo_mysql',
    'charset' => 'utf8',
    'host' => 'localhost',
    'port' => '3306',
    'dbname' => 'microcms',
    'user' => 'microcms_user',
    'password' => 'secret',
);
