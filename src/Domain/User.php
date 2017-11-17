<?php

namespace silex_openC\src\Domain;

use Symfony\Component\Security\Core\User\UserInterface;

class User implements UserInterface {

    /**
     * User id
     *
     * @var integer
     */
    private $id;

    /**
     * User name
     *
     * @var string
     */
    private $username;

    /**
     * User Password
     *
     * @var string
     */
    private $password;

    /**
     * Salt originally used to encode the password
     *
     * @var string
     */
    private $salt;

    /**
     * Role
     * Values : ROLE_USER or ROLE_ADMIN
     *
     * @var string
     */
    private $role;

    public function getId() {
        return $this->id;
    }

    /**
     * @inheritDoc
     */
    public function getUsername() {
        return $this->username;
    }

    /**
     * @inheritDoc
     */
    public function getPassword() {
        return $this->password;
    }

    /**
     * @inheritDoc
     */
    public function getSalt() {
        return $this->salt;
    }

    public function getRole() {
        return $this->role;
    }

    public function setId($id) {
        $this->id = $id;
        return $this;
    }

    public function setUsername($username) {
        $this->username = $username;
        return $this;
    }

    public function setPassword($password) {
        $this->password = $password;
        return $this;
    }

    public function setSalt($salt) {
        $this->salt = $salt;
        return $this;
    }

    public function setRole($role) {
        $this->role = $role;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getRoles() {
        return [$this->getRole()];
    }

    /**
     * @inheritDoc
     */
    public function eraseCredentials() {
        // nothing to do or add here
    }

}

// On constate une différence importante avec les classes métier Article et Comment : la classe User implémente l'interface Symfony UserInterface et définit les méthodes présentes dans cette interface (ces méthodes sont identifiées par des @inheritDoc dans les commentaires de la classe User). Ces méthodes sont indispensables pour que l'utilisateur puisse être authentifié et autorisé par Symfony.