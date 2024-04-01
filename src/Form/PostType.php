<?php

namespace App\Form;

use App\Entity\Forum;
use App\Entity\Post;
use App\Entity\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('message')
            ->add('nbLike')
            ->add('idForum', EntityType::class, [
                'class' => 'App\Entity\Forum',
                'choice_label' => 'id', // Replace 'name' with the property you want to display in the dropdown
            ])
            ->add('idUser', EntityType::class, [
                'class' => 'App\Entity\Utilisateur',
                'choice_label' => 'username', // Replace 'username' with the property you want to display in the dropdown
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
