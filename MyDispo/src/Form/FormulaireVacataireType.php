<?php

namespace App\Form;

use App\Entity\FormulaireVacataire;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;

class FormulaireVacataireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('echelleCalendrier',IntegerType::class,['label' => 'Nombre de minutes entre chaque case du calendrier',])
            ->add('heureDebutCalendrier',TimeType::class,['label' => 'Heure de début du calendrier',])
            ->add('heureFinCalendrier',TimeType::class,['label' => 'Heure de fin du calendrier',])
            ->add('texteHebdomadaire',TextareaType::class,array(
                'label' => 'Texte affiché pour la saisie des contraintes hebdomadaires',))
            ->add('textePonctuel',TextareaType::class,['label' => 'Texte affiché pour la saisie des contraintes ponctuelles',])
            ->add('anneeUniversitaire',TextType::class,['label' => 'Année universitaire du formulaire',])
            ->add('remarquesHebdoActives',ChoiceType::class, [
                'choices' => array(
                    "Oui" => true,
                    "Non" => false,
                ), 'label' => 'Activer les remarques éventuelles pour les contraintes hebdomadaires'
            ])
            ->add('remarquesPonctuelActives',ChoiceType::class, [
                'choices' => array(
                    "Oui" => true,
                    "Non" => false,
                ), 'label' => 'Activer les remarques éventuelles pour les contraintes ponctuelles'
            ])

            ->add('estOuvert',ChoiceType::class, [
                'choices' => array(
                    "Oui" => true,
                    "Non" => false,
                ), 'label' => 'Ouvrir les saisies'
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
