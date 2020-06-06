<?php

namespace App\Form;

use App\Entity\FormulaireTitulaire;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;



class FormulaireTitulaireType extends AbstractType
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
      ->add('remarquesHebdoActives',CheckboxType::class, [
        'label_attr' => ['class' => 'switch-custom'], 'label' => 'Activer les remarques éventuelles pour les contraintes hebdomadaires'
      ])
      ->add('remarquesPonctuelActives',CheckboxType::class, [
        'label_attr' => ['class' => 'switch-custom'], 'label' => 'Activer les remarques éventuelles pour les contraintes ponctuelles'
      ])

      ->add('estOuvert',CheckboxType::class, [
        'label_attr' => ['class' => 'switch-custom'], 'label' => 'Ouvrir les saisies'
      ])
      ->add('quantiteProForte',IntegerType::class,['label' => 'Nombre de créneaux autorisé pour la saisie des contraintes professionnelles de priorité forte',])
      ->add('quantiteProMoy',IntegerType::class,['label' => 'Nombre de créneaux autorisé pour la saisie des contraintes professionnelles de priorité moyenne',])
      ->add('quantiteProFaible',IntegerType::class,['label' => 'Nombre de créneaux autorisé pour la saisie des contraintes professionnelles de priorité faible',])
      ->add('quantitePersForte',IntegerType::class,['label' => 'Nombre de créneaux autorisé pour la saisie des préférences personnelles de priorité forte',])
      ->add('quantitePersMoy',IntegerType::class,['label' => 'Nombre de créneaux autorisé pour la saisie des préférences personnelles de priorité forte',])
      ->add('quantitePersFaible',IntegerType::class,['label' => 'Nombre de créneaux autorisé pour la saisie des préférences personnelles de priorité forte',])
      ->add('dureeProForte',IntegerType::class,['label' => 'Durée autorisée pour les contraintes professionnelles de priorité forte',])
      ->add('dureeProMoy',IntegerType::class,['label' => 'Durée autorisée pour les contraintes professionnelles de priorité moyenne',])
      ->add('dureeProFaible',IntegerType::class,['label' => 'Durée autorisée pour les contraintes professionnelles de priorité faible',])
      ->add('dureePersForte',IntegerType::class,['label' => 'Durée autorisée pour les préférences personnelles de priorité forte',])
      ->add('dureePersMoy',IntegerType::class,['label' => 'Durée autorisée pour les préférences personnelles de priorité moyenne',])
      ->add('dureePersFaible',IntegerType::class,['label' => 'Durée autorisée pour les préférences personnelles de priorité faible',])

      ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
      $resolver->setDefaults([
        'data_class' => FormulaireTitulaire::class,
      ]);
    }
  }
