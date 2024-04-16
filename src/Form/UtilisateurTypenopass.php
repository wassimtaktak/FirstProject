<?php

namespace App\Form;

use App\Entity\Utilisateur;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class UtilisateurTypenopass extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class, [
                'label' => 'Nom d\'utilisateur :',
                'label_attr' => ['class' => 'col-sm-4 label-white'],
                'attr' => ['class' => 'form-control col-sm-6', 'id' => 'username', 'placeholder' => 'Nom d\'utilisateur'],
                'row_attr' => ['class' => 'mb-3 row'],
                'required' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir votre nom d\'utilisateur.',
                    ]),
                ],
            ])

            ->add('email', TextType::class, [
                'label' => 'Adresse email :',
                'label_attr' => ['class' => 'col-sm-4 label-white'],
                'attr' => ['class' => 'form-control col-sm-6', 'id' => 'email', 'placeholder' => 'Adresse email'],
                'row_attr' => ['class' => 'mb-3 row'],
                'required' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir votre adresse email.',
                    ]),
                    new Email([
                        'message' => 'Veuillez saisir une adresse email valide.',
                    ]),
                ],
            ])
            ->add('nom', TextType::class, [
                'label' => 'Nom :',
                'label_attr' => ['class' => 'col-sm-4 label-white'],
                'attr' => ['class' => 'form-control col-sm-6', 'id' => 'nom', 'placeholder' => 'Nom'],
                'row_attr' => ['class' => 'mb-3 row'],
                'required' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir votre nom.',
                    ]),
                    new Regex([
                        'pattern' => '/^[a-zA-ZÀ-ÿ\s\-]+$/',
                        'message' => 'Le nom ne doit contenir que des caractères.',
                    ]),
                ],
            ])
            ->add('prenom', TextType::class, [
                'label' => 'Prénom :',
                'label_attr' => ['class' => 'col-sm-4 label-white'],
                'attr' => ['class' => 'form-control col-sm-6', 'id' => 'prenom', 'placeholder' => 'Prénom'],
                'row_attr' => ['class' => 'mb-3 row'],
                'required' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir votre prénom.',
                    ]),
                    new Regex([
                        'pattern' => '/^[a-zA-ZÀ-ÿ\s\-]+$/',
                        'message' => 'Le prénom ne doit contenir que des caractères.',
                    ]),
                ],
            ])
            ->add('telephone', TelType::class, [
                'label' => 'Téléphone :',
                'label_attr' => ['class' => 'col-sm-4 label-white'],
                'attr' => ['class' => 'form-control col-sm-6', 'id' => 'telephone', 'placeholder' => 'Téléphone'],
                'row_attr' => ['class' => 'mb-3 row'],
                'required' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir votre numéro de téléphone.',
                    ]),
                    new Regex([
                        'pattern' => '/^\d{8}$/',
                        'message' => 'Le numéro de téléphone ne doit contenir que des chiffres.',
                    ]),
                ],

            ])
            ->add('idrole', EntityType::class, [
                'class' => 'App\Entity\Role',
                'choice_label' => 'role',
                'label' => 'Rôle :',
                'label_attr' => ['class' => 'col-sm-4 label-white'],
                'attr' => ['class' => 'form-control col-sm-6', 'id' => 'idrole'],
                'row_attr' => ['class' => 'mb-3 row'],
                'required' => true
            ]);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
        ]);
    }
}
