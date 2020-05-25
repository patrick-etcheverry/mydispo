<?php

namespace App\Form;

use App\Entity\LogEnseignant;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LogEnseignantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dateLog')
            ->add('typeAction')
            ->add('zone')
            ->add('description')
            ->add('enseignant')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => LogEnseignant::class,
        ]);
    }
}
