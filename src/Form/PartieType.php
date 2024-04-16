<?php

namespace App\Form;

use App\Entity\Partie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PartieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('scoreequipe1', null, [
                'attr' => ['class' => 'form-control', 'id' => 'scoreequipe1']
            ])
            ->add('scoreequipe2', null, [
                'attr' => ['class' => 'form-control', 'id' => 'scoreequipe2']
            ])
            ->add('commentaire', null, [
                'attr' => ['class' => 'form-control', 'id' => 'commentaire']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Partie::class,
        ]);
    }
}
