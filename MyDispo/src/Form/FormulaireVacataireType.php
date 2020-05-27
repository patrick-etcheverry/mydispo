<?php

namespace App\Form;

use App\Entity\FormulaireVacataire;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FormulaireVacataireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('echelleCalendrier')
            ->add('texteHebdomadaire')
            ->add('textePonctuel')
            ->add('remarquesHebdoActives')
            ->add('remarquesPonctuelActives')
            ->add('estOuvert')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => FormulaireVacataire::class,
        ]);
    }
}
