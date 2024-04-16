<?php

namespace App\Form;

use App\Entity\Forum;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
class ForumType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('sujet',TextareaType::class, [
                'label' => 'sujet :',
                'label_attr' => ['class' => 'col-sm-4 label-white'],
                'attr' => ['class' => 'form-control col-sm-6', 'id' => 'sujet', 'placeholder' =>'sujet'],
                'row_attr' => ['class' => 'mb-3 row'], 'required' => true
            ])
            ->add('dateCreation', TextType::class, [
                'label' => 'Date Creation :',
                'label_attr' => ['class' => 'col-sm-4 label-white'],
                'attr' => ['class' => 'form-control col-sm-6 datepicker', 'id' => 'dateCreation', 'placeholder' =>'Date Creation'],
                'row_attr' => ['class' => 'mb-3 row'], 'required' => true
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Forum::class,
        ]);
    }
}
