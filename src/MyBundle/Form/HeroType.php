<?php

namespace MyBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class HeroType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder,  array $options)
    {
        $builder->add('name')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class'   =>  'MyBundle\Entity\Hero',
                'csrf_protection'   =>  false
            ]
        );
    }


}