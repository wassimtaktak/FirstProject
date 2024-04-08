<?php

namespace App\Form;

use App\Entity\Tournoi;
use App\Entity\Jeu;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TournoiType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
{
    $builder
        ->add('name', TextType::class, [
            'label' => 'Nom',
            'attr' => ['class' => 'form-control', 'id' => 'name']
        ])
        ->add('regles', TextareaType::class, [
            'label' => 'Règles',
            'attr' => ['class' => 'form-control', 'id' => 'regles']
        ])
        ->add('jour', DateType::class, [
            'label' => 'Jour',
            'widget' => 'single_text',
            'format' => 'yyyy-MM-dd',
            'attr' => ['class' => 'form-control', 'id' => 'jour']
        ])
        ->add('prize', TextType::class, [
            'label' => 'Prix',
            'attr' => ['class' => 'form-control', 'id' => 'prize']
        ])
        ->add('tempsdeb', null, [
            'label' => 'Temps début',
            'attr' => ['class' => 'form-control', 'id' => 'tempsdeb']
        ])
        ->add('registration', ChoiceType::class, [
            'label' => 'Inscription',
            'choices' => [
                'Ouverte' => 'ouverte',
                'Fermée' => 'fermée',
            ],
            'required' => true,
            'attr' => ['class' => 'form-select form-select-sm mb-3', 'id' => 'registration']
        ])
        ->add('jpt', null, [
            'label' => 'joueur par équipe ',
            'attr' => ['class' => 'form-control', 'id' => 'jpt']
        ])
        ->add('nbrequipe', ChoiceType::class, [
            'label' => 'Nombre d\'équipes',
            'choices' => [
                '2' => '2',
                '4' => '4',
                '8' => '8',
            ],
            'required' => true,
            'attr' => ['class' => 'form-select form-select-sm mb-3', 'id' => 'nbrequipe']
        ])
        ->add('idjeu', EntityType::class, [
            'label' => 'Jeu',
            'class' => Jeu::class,
            'required' => true,
            'attr' => ['class' => 'form-control', 'id' => 'idjeu']
        ]);
}
        
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Tournoi::class,
        ]);
    }
}
