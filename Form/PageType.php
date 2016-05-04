<?php

namespace SKCMS\CoreBundle\Form;

use SKCMS\CoreBundle\Entity\PageTemplate;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use SKCMS\CoreBundle\Entity\EntityList;
use SKCMS\CoreBundle\Entity\Menu;

class PageType extends EntityType
{
    public function __construct()
    {
        
    }
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')

            ->add('template','entity',['class'=>PageTemplate::class,'required'=>false])
            ->add('lists','entity',['class'=>EntityList::class,'required'=>false,'multiple'=>true])
            ->add('menus','entity',['class'=>Menu::class,'required'=>false,'multiple'=>true])
            ->add('slug','skscms_protecedinput',['required'=>false])
            ->add('minRoleAccess','choice',['choices'=>['ANON'=>'anonyme','ROLE_USER'=>'user','ROLE_CLIENT'=>'client','ROLE_ADMIN'=>'admin']])
            ->add('redirectRoute',null,['required'=>false])
            ->add('forward',null,['required'=>false])
            ->add('seoTitle',null,['required'=>false])
            ->add('seoDescription','textarea',['required'=>false])

        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SKCMS\CoreBundle\Entity\SKBasePage'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'skcms_corebundle_page';
    }
}
