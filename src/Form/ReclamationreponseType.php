<?php

namespace App\Form;

use App\Entity\Reclamationreponse;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class ReclamationreponseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('reponse')
            //->add('idReclamation', EntityType::class, [
           //     'class' => 'App\Entity\Reclamations',
            //    'choice_label' => 'sujet', // Replace 'name' with the property you want to display in the dropdown
            /*])
            ->add('idUser', EntityType::class, [
                'class' => 'App\Entity\Utilisateur',
                'choice_label' => 'username', // Replace 'name' with the property you want to display in the dropdown
            ])*/
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reclamationreponse::class,
        ]);
    }
}
