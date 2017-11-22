<?php

/**
 * Formulaire d'ajout d'un nouvel article (pour l'admin)
 * les 2 champs du formulaire correspondent aux propriétés d'un article (title et content)
 * TextType et TextareaType sont des classes Symfony
 * Ce formulaire s'affiche dans views/article_form.html.twig
 */

namespace silex_openC\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ArticleType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('title', TextType::class)
                ->add('content', TextareaType::class);
    }

    public function getName() {
        return 'article';
    }

}
