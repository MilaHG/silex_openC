<?php

namespace silex_openC\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class CommentType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('content', TextareaType::class);
    }

// Notre classe hérite de la classe Symfony AbstractType et redéfinit sa méthode buildForm qui, comme son nom l'indique, permet de construire un formulaire. Ici, le formulaire aura une zone de texte associée au contenu du commentaire (champ content de type textarea).
// Le nom de la zone de texte ("content") n'est pas choisi au hasard : il correspond exactement à la propriétécontent de la classe métierComment. C'est indispensable pour que Symfony puisse associer notre formulaire à une instance deComment.

    public function getName() {
        return 'comment';
    }

}
