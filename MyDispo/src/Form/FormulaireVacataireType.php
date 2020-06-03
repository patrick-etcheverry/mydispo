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
            ->add('echelleCalendrier',IntegerType::class,['label' => 'Nombre de minutes entre chaque case du calendrier',])
            ->add('texteHebdomadaire',TextareaType::class,array(
                'label' => 'Texte affiché pour la saisie des contraintes hebdomadaires',))
            ->add('textePonctuel',TextareaType::class,['label' => 'Texte affiché pour la saisie des contraintes ponctuelles',])
            ->add('remarquesHebdoActives',CheckboxType::class, [
                'label_attr' => ['class' => 'switch-custom'], 'label' => 'Activer les remarques éventuelles pour les contraintes hebdomadaires'
            ])
            ->add('remarquesPonctuelActives',CheckboxType::class, [
                'label_attr' => ['class' => 'switch-custom'], 'label' => 'Activer les remarques éventuelles pour les contraintes ponctuelles'
            ])

            ->add('estOuvert',CheckboxType::class, [
                'label_attr' => ['class' => 'switch-custom'], 'label' => 'Ouvrir les saisies'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => FormulaireVacataire::class,
        ]);
    }
}
