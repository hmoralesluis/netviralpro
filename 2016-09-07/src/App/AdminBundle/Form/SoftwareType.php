<?php

namespace App\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SoftwareType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', array('attr' => array('class' => 'm-wrap large')))
            ->add('file', 'file', array('attr' => array('class' => 'm-wrap large')))
            ->add('description', 'textarea', array('attr' => array('class' => 'm-wrap large')))
            ->add('plataform', 'text', array('attr' => array('class' => 'm-wrap large')))
            ->add('activationCode', 'text', array('attr' => array('class' => 'm-wrap large not-required')))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'App\AdminBundle\Entity\Software'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'app_adminbundle_software';
    }
}
