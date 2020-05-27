<?php

namespace App\Form;

use App\Entity\ModeleMail;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;


class ModeleMailType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom',TextType::class, array(
      'label' => 'Nom du modèle de mail',
      ))
            ->add('sujet',TextType::class, array(
      'label' => 'Sujet',
      ))
            ->add('contenu',TextareaType::class, array(
      'label' => 'Message',
      ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ModeleMail::class,
        ]);
    }
}
