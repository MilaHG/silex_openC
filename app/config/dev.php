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
