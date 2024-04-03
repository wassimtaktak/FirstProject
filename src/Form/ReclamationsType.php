<?php

namespace App\Form;

use App\Entity\Reclamations;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ReclamationsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('sujet')
            ->add('dateCreation')
            ->add('status')
            ->add('message', TextareaType::class, [
                'attr' => ['rows' => 8],
            ])
            ->add('captureecranpath')
            ->add('idUser', EntityType::class, [
                'class' => 'App\Entity\Utilisateur',
                'choice_label' => 'username', // Replace 'username' with the property you want to display in the dropdown
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reclamations::class,
        ]);
    }
}
