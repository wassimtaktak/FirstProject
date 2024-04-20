<?php

namespace App\Form;

use App\Entity\Role;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class RoleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('role', TextType::class, [
                'label' => 'Role',
                'label_attr' => ['class' => 'col-sm-4 label-white'],
                'attr' => ['class' => 'form-control col-sm-6', 'id' => 'role', 'placeholder' => 'Role'],
                'row_attr' => ['class' => 'mb-3 row'],
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir votre nom.',
                    ]),
                    new Regex([
                        'pattern' => '/^[a-zA-ZÀ-ÿ\s\-]+$/',
                        'message' => 'Le nom du role ne doit contenir que des caractères.',
                    ]),
                ],
            ]);
    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Role::class,
        ]);
    }
}
