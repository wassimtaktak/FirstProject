<?php

namespace App\Form;
use App\Entity\Jeu;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class JeuType extends AbstractType
{
   public function buildForm(FormBuilderInterface $builder, array $options): void
{
    $imagejeuDefault = $options['imagejeu_default'];
    $builder
        ->add('nom', TextType::class, [
            'label' => 'Nom jeu',
            'required' => false,
            'attr' => ['class' => 'form-control', 'id' => 'floatingInput']
        ])
        ->add('imagejeu', FileType::class, [
            'label' => 'Image du jeu',
            'attr' => ['class' => 'form-control bg-dark', 'id' => 'formFile'],
            'required' => false,
            'data_class' => null,
            'empty_data' => $imagejeuDefault,
        ]);
     
}

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Jeu::class,
            'imagejeu_default' => null,
        ]);
    }
}
