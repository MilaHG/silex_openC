<?php

/*
 * tests qui vérifieront uniquement que l'application répond sans erreur
 * aux différentes routes possibles
 * composer.json mis à jour avec le bloc "require-dev" (que pour le dev pas en prod)
 */

namespace silex_openC\Tests;

require_once __DIR__ . '/../../vendor/autoload.php';

use Silex\WebTestCase;

class AppTest extends WebTestCase {

    /**
     * Test simple inspiré des bonnes pratiques Symfony.
     * Vérifie que toutes les URL (routes) sont correctement chargées.
     * Pendant l'exécution des tests cette méthode est appelée pour chaque URL retournée
     * par la méthode provideUrls().
     * Pour les URL admin il faut que chaque table ait un ID = 1.
     *
     * @dataProvider provideUrls
     */
    public function testPageIsSuccessful($url) {
        $client = $this->createClient();
        $client->request('GET', $url);
        $this->assertTrue($client->getResponse()->isSuccessful());
    }

    /**
     * {@inheritDoc}
     */
    public function createApplication() {
        $app = new \Silex\Application();

        require __DIR__ . '/../../app/config/dev.php';
        require __DIR__ . '/../../app/app.php';
        require __DIR__ . '/../../app/routes.php';

        // génère des exceptions au lieu de pages HTML si une erreur se produit
        unset($app['exception_handler']);
        // simule des sessions pour faire les tests
        $app['session.test'] = true;
        // permet l'accès anonyme au back office ADMIN
        $app['security.access_rules'] = [];

        return $app;
    }

    /**
     * Fournit toutes les URL valides de l'application
     *
     * @return array Liste des URL valides du site
     */
    public function provideUrls() {

        return [['/'],
            ['/article/1'],
            ['/login'],
            ['/admin'],
            ['/admin/article/add'],
            ['/admin/article/1/edit'],
            ['/admin/comment/1/edit'],
            ['/admin/user/add'],
            ['/admin/user/1/edit']
        ];
    }

}

/*
 * Ce fichier définit une classe AppTest dérivée de WebTestCase.
 * Sa méthode createApplication instancie, configure et renvoie notre application sous Silex.
 * Sa méthode provideUrls définit toutes les URL à tester : elles correspondent aux routes
 * de notre application accessibles via la commande HTTP GET.
 * Les routes testées ici correspondent à la page d'accueil, l'affichage de l'article ayant
 * l'identifiant 1, le formulaire de connexion et les différentes pages du back-office.
 * Enfin, la méthode testPageIsSuccessful de la classe AppTest instancie un client et
 * vérifie (méthode PHPUnitAssertTrue) que la réponse HTTP renvoyée pour chaque URL à tester
 * indique un succès.
 * Dans le cas contraire, le test échouera.
 * Lors du lancement d'un test, PHPUnit exécute automatiquement les méthodes commençant par test.
 */