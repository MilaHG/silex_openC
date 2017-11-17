<?php

namespace silex_openC\src\DAO;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use silex_openC\src\Domain\User;

class UserDAO extends DAO implements UserProviderInterface {

    /**
     * Returns a user matching the supplied ID
     *
     * @param integer $id The user id
     *
     * @return \silex_openc\src\Domain\User|thows an exception if no matching user is found
     */
    public function find($id) {
        $sql = "SELECT * FROM t_user WHERE usr_id=?";
        $row = $this->getDb()->fetchAssoc($sql, [$id]);

        if ($row) {
            return $this->buildDomainObject($row);
        } else {
            throw new \Exception("Pas d'utilisateur connu avec l'id " . $id);
        }
    }

    /**
     * Returns a list of all users, sorted by role and name - for the Admin
     *
     * @return array A list of all users
     */
    public function findAll() {
        $sql = "SELECT * FROM t_user ORDER BY usr_role, usr_name";
        $result = $this->getDb()->fetchAll($sql);

        // Convert query result in an array of domain objects
        $entities = array();
        foreach ($result as $row) {
            $id = $row['usr_id'];
            $entities[$id] = $this->buildDomainObject($row);
        }
        return $entities;
    }

    /**
     * {@inheritDoc}
     */
    public function loadUserByUsername($username) {
        $sql = "SELECT * FROM t_user WHERE usr_name=?";
        $row = $this->getDb()->fetchAssoc($sql, [$username]);

        if ($row) {
            return $this->buildDomainObject($row);
        } else {
            throw new UsernameNotFoundException(sprintf('Utilisateur "%s" non trouvé.', $username));
        }
    }

    /**
     * {@inheritDoc}
     */
    public function refreshUser(UserInterface $user) {
        $class = get_class($user);

        if (!$this->supportsClass($class)) {
            throw new UnsupportedUserException(sprintf('Les instanciations de "%s" ne sont pas supportées.', $class));
        } else {
            return $this->loadUserByUsername($user->getUsername());
        }
    }

    /**
     * {@inheritDoc}
     */
    public function supportsClass($class) {
        return 'silex_openC\src\Domain\User' === $class;
    }

    /**
     * Creates a user object based on a DB row
     *
     * @param array $row The DB row containing the User data
     * @return \silex_openC\src\Domain\User
     */
    protected function buildDomainObject(array $row) {
        $user = new User();
        $user->setId($row['usr_id']);
        $user->setUsername($row['usr_name']);
        $user->setPassword($row['usr_password']);
        $user->setSalt($row['usr_salt']);
        $user->setRole($row['usr_role']);
        return $user;
    }

}

// Cette classe reprend la structure de nos classes DAO existantes et implémente l'interface Symfony UserProviderInterface. Cette interface contient les méthodes nécessaires pour qu'une classe puisse être utilisée comme fournisseur de données utilisateur par le composant de gestion de la sécurité de Symfony
// au cours du processus d'authentification.