<?php

namespace App\Form;

use App\Entity\Equipe;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EquipeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('nom', TextType::class, [
            'label' => 'Nom de votre équipe :',
            'label_attr' => ['class' => 'col-sm-4 label-white'],
            'attr' => ['class' => 'form-control col-sm-6', 'id' => 'name', 'placeholder' =>'Nom de votre équipe'],
            'row_attr' => ['class' => 'mb-3 row'], 'required' => true
        ])
        ->add('associationname', TextType::class, [
            'label' => 'Nom de l\'association :',
            'label_attr' => ['class' => 'col-sm-4 label-white'],
            'attr' => ['class' => 'form-control col-sm-6', 'id' => 'associationname', 'placeholder' =>'Nom de l\'association'],
            'row_attr' => ['class' => 'mb-3 row'], 'required' => true
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Equipe::class,
        ]);
    }
}
