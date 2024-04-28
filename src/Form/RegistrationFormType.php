<?php

namespace App\Form;

use App\Entity\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Form\Extension\Core\Type\TelType;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class, [
                'label' => 'Nom d\'utilisateur :',
                'label_attr' => ['class' => 'col-sm-4 label-black', 'style' => 'color : white'],
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
                'label_attr' => ['class' => 'col-sm-4 label-white', 'style' => 'color : white'],
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
                'label_attr' => ['class' => 'col-sm-4 label-white', 'style' => 'color : white'],
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
                'label' => 'Prenom :',
                'label_attr' => ['class' => 'col-sm-4 label-white', 'style' => 'color : white'],
                'attr' => ['class' => 'form-control col-sm-6', 'id' => 'Prenom', 'placeholder' => 'Prenom'],
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
            ->add('telephone', TelType::class, [
                'label' => 'Téléphone :',
                'label_attr' => ['class' => 'col-sm-4 label-white', 'style' => 'color : white'],
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
            ->add('plainPassword', PasswordType::class, [
                'mapped' => false,
                'label' => 'Passwrod : ',
                'label_attr' => ['class' => 'col-sm-4 label-white', 'style' => 'color : white'],
                'row_attr' => ['class' => 'mb-3 row'],
                'attr' => ['class' => 'form-control col-sm-6', 'id' => 'password', 'placeholder' => 'Password'],
                'required' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',

                        'max' => 4096,
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
        ]);
    }
}
