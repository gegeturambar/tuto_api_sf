<?php

namespace MyBundle\Form;

use MyBundle\Entity\Place;
use MyBundle\Entity\Theme;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

Form;

class ThemePlaceMapType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder,  array $options)
    {
        $builder->add('value')
            ->add('theme',EntityType::class, array(
                'class'=> 'MyBundle\Entity\Theme',
                'choice_label'=>    'name'
            ))
            ->add('place',EntityType::class,array(
                'class' =>  'MyBundle\Entity\Place'
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class'   =>  'MyBundle\Entity\ThemePlaceMap',
                'csrf_protection'   =>  false
            ]
        );
    }


}