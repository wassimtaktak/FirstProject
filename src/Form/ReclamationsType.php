<?php

namespace App\Form;

use App\Entity\Reclamations;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;

class ReclamationsType extends AbstractType
{
   public function buildForm(FormBuilderInterface $builder, array $options): void
{
    $builder
        ->add('sujet', ChoiceType::class, [
            'choices' => [
                'Jeu' => 'Jeu',
                'Tournoi' => 'Tournoi',
                'Equipe' => 'Equipe',
                'Compte' => 'Compte',
                'Forum' => 'Forum',
                // Ajoutez d'autres sujets selon vos besoins
            ],
            'placeholder' => 'Choisir un sujet', // Optionnel : Ajoute un libellé par défaut
            'required' => true, 
        ])
        ->add('message', TextareaType::class, [
            'attr' => ['rows' => 10], 
            'required' => true, 
            
        ])
       // ->add('dateCreation')
        //->add('status')
       ->add('captureecranpath', FileType::class, [
        'label' => 'Capture ecran',
        'attr' => ['class' => 'form-control bg-dark', 'id' => 'formFile'],
        'required' => false,
        'data_class' => null,
     ])
        
    ->add('idUser', EntityType::class, [
         'class' => 'App\Entity\Utilisateur',
        'choice_label' => 'username', // Replace 'username' with the property you want to display in the dropdown
        ]);
}
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reclamations::class,
        ]);
    }
}
