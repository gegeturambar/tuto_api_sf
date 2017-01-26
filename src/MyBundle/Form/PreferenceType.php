<?php

namespace MyBundle\Form;

use MyBundle\Entity\Theme;
use MyBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

Form;

class PreferenceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder,  array $options)
    {
        $builder->add('value')
            ->add('theme',Theme::class)
            ->add('user',User::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class'   =>  'MyBundle\Entity\Preference',
                'csrf_protection'   =>  false
            ]
        );
    }


}