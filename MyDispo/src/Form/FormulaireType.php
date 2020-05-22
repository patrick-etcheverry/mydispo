<?php

namespace App\Form;

use App\Entity\Formulaire;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FormulaireType extends AbstractType
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
            ->add('quantiteProForte')
            ->add('quantiteProMoy')
            ->add('quantiteProFaible')
            ->add('quantitePersForte')
            ->add('quantitePersMoy')
            ->add('quantitePersFaible')
            ->add('dureeProForte')
            ->add('dureeProMoy')
            ->add('dureeProFaible')
            ->add('dureePersForte')
            ->add('dureePersMoy')
            ->add('dureePersFaible')
            ->add('enseignant')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Formulaire::class,
        ]);
    }
}
