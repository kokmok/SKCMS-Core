<?php

namespace SKCMS\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class EntityListType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    
    private $configParams;
    private $configModules;
    private $entityValues;
    private $orderBy;
    
    public function __construct($configParams,$configModules)
    {
        $this->entityValues = [];
        $this->orderBy = [];
//        die(print_r($configParams,true));
        $this->configParams = $configParams;
        $this->configModules= $configModules;
        $this->configValues();
    }
    
    public function configValues()
    {
        foreach ($this->configParams as $configName => $configValues)
        {
            $this->entityValues[$configName] = $configValues['beautyName'];
            
            foreach ($configValues['listProperties'] as $properties)
            {
                $this->orderBy[$configName][$properties['dataName']] = $properties['beautyName'];
            }
        }
        if ($this->configModules['blog']['enabled']){
            $this->entityValues['BlogPost'] = 'Blog Post';
        }

        
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('entity','choice',['choices'=> $this->entityValues])
            ->add('limit')
            ->add('order','choice',['choices'=>['DESC'=>'DESC','ASC'=>'ASC']])
            ->add('orderBy','choice',['choices'=> $this->orderBy])
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SKCMS\CoreBundle\Entity\EntityList'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'skcms_corebundle_entitylist';
    }
}
