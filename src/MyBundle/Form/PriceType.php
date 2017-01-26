<?php

namespace MyBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

Form;

class PriceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder,  array $options)
    {
        $builder->add('type')
            ->add('value');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class'   =>  'MyBundle\Entity\Price',
                'csrf_protection'   =>  false
            ]
        );
    }


}