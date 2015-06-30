<?php

namespace SKCMS\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class MenuElementType extends AbstractType
{
    
    private $pagesId;
    private $groups;
    
    public function __construct($entities)
    {
        $this->groups = [];
        foreach ($entities as $entityGroupName => $entityGroup)
        {
            $this->groups[$entityGroupName] = [];
            foreach ($entityGroup as $entity)
            {
                $this->groups[$entityGroupName][$entity->getId()] = $entity; 
            }
            
        }
        
        
    }
    
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('textId')
            ->add('position')
            ->add('entityId','choice',['choices'=>$this->groups])
            ->add('parent')
        ;
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
        return 'skcms_corebundle_menuelement';
    }
}
