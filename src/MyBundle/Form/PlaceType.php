<?php

namespace MyBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class PlaceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder,  array $options)
    {
        $builder->add('name')
            ->add('address')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class'   =>  'MyBundle\Entity\Place',
                'csrf_protection'   =>  false
            ]
        );
    }


}