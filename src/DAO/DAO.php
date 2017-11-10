<?php

namespace silex_openC\src\DAO;

use Doctrine\DBAL\Connection;

abstract class DAO {

    /**
     * Database connection
     *
     * @var \Doctrine\DBAL\Connection
     */
    private $db;

    /**
     * Constructor
     *
     * @param \Doctrine\DBAL\Connection The database connection object
     */
    public function __construct(Connection $db) {
        $this->db = $db;
    }

    /**
     * Grants access to the database connection object
     *
     * @return \Doctrine\DBAL\Connection The database connection object
     */
    protected function getDb() {
        return $this->db;
    }

    /**
     * Builds a domain object from a DB row
     * Must be overridden by child classes
     */
    protected abstract function buildDomainObject(array $row);
}

/**
 * une fois identifiés les besoins
 * communs à toutes les classes d'accès
 * aux données :
 * - connexion à la BDD
 * - construction d'un objet à partir
 * d'une ligne de résultat SQL
 * nous factorisons ceux-ci au sein d'une
 * classe abstraite DAO dont hériteront
 * toutes les classes d'accès aux données
 * de la BDD
 *
 * La connexion à la base de données est
 * encapsulée sous la forme d'une
 * propriété privée $db et d'un accesseur
 * protégé (donc accessible uniquement
 * aux classes dérivées) getDb.
 * La construction d'un objet du domaine
 * est spécifique à chaque entité métier
 * : on factorise donc uniquement la
 * déclaration de ce service (méthode
 * protégée buildDomainObject).
 * /!\ Chaque classe d'accès aux données
 * (Article, Comment...) devra redéfinir
 * cette méthode pour consstruire un
 * objet du domaine particulier.
 */