<?php

namespace SKCMS\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class MenuElementChildrenType extends AbstractType
{
    
    
    private $choices;
    
    public function __construct($choices)
    {
        
        $this->choices = $choices;
        
       
    }
    
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->setAttribute('choices',$this->choices);
        
        
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SKCMS\CoreBundle\Entity\MenuElement'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'menuelementchildren';
    }
}
