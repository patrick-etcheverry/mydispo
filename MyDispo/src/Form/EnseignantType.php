<?php

namespace App\Form;

use App\Entity\Enseignant;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Formation;

class EnseignantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom',TextType::class,array(
                'label' => 'Nom',))
            ->add('prenom',TextType::class,array(
                'label' => 'Prénom',))
                ->add('mail',EmailType::class,array(
                    'label' => 'Adresse mail',))
                    ->add('statut', ChoiceType::class, array(
                        'choices'  => array(
                            'Titulaire' => "Titulaire",
                            'Vacataire' => "Vacataire"
                    ),
                       'label' => 'Statut de l\'enseignant'
                  ))
                  ->add('enSommeil', ChoiceType::class, array(
                      'choices'  => array(
                           'Actif' => false,
                          'Inactif' => true,),
                      'help' => "Un enseignant en mode sommeil ne recevra aucun mail de la part de l'application",
                      'label' => 'Etat de l\'enseignant'
                  ))
                  ->add('token',TextType::class,array(
                      'label' => 'Lien personnalisé',))
            ->add('formations', EntityType::class, array(
              'class' => Formation::class,
              'multiple' => true,
              'expanded' => true,
              'help' => "La ou les formations dans lesquelles l'enseignant intervient",))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Enseignant::class,
        ]);
    }
}
