<?php

namespace App\Form;

use App\Entity\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;


class InvitationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('username', TextType::class, [
            'label' => 'Nom du joueur invité :',
            'label_attr' => ['class' => 'col-sm-4 label-white'],
            'attr' => ['class' => 'form-control col-sm-6', 'id' => 'name', 'placeholder' =>'username de votre invité'],
            'row_attr' => ['class' => 'mb-3 row'], 'required' => true
        ])    ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => null,
        ]);
    }
}
