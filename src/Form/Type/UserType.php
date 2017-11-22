<?php

/*
 * Formulaire de gestion des utilisateurs - Partie ADMIN
 * Fichiers liés :
 * views/user_form.html.twig => formulaire de gestion des utilisateurs
 * views/admin.html.twig => affichage des actions de gestion utilisateur
 * src/DAO/UserDAO.php => modif et supp d'1 utilisateur dans la classe UserDAO
 * src/DAO/CommentDAO.php => supp tous commentaires liés à 1 utilisateur
 * app/routes.php => contrôleurs pour ajout/modif/supp d'un utilisateur
 */

namespace silex_openC\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilder;

class UserType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('username', TextType::class)
                ->add('password', RepeatedType::class, [// repeated gère la double saisie du MDP (nécessite le composant VALIDATOR de Symfony cf. composer.json ligne 13
                    'type' => PasswordType::class,
                    'invalid_message' => 'Les 2 mots de passe doivent être identiques.',
                    'options' => ['required' => true],
                    'first_options' => ['label' => 'Saisir le mot de passe'],
                    'second_options' => ['label' => 'Saisir à nouveau le mot de passe.']
                ])
                ->add('role', ChoiceType::class, [
                    'choices' => [
                        'Admin' => 'ROLE_ADMIN',
                        'User' => 'ROLE_USER']
        ]);
    }

    public function getName() {
        return 'user';
    }

}
