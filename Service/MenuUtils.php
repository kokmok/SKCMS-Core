<?php

namespace SKCMS\CoreBundle\Service;

use SKCMS\CoreBundle\Entity\SKBasePage;
/**
 * Description of ListUtils
 *
 * @author Jona
 */
class MenuUtils 
{

    private $container;
    private $em;
    private $locale;
    
    public function __construct($container)
    {
        $this->container = $container;
        $this->em = $container->get('doctrine')->getManager();
        if ($container->isScopeActive('request'))
        {
            $this->locale = $this->container->get('request')->getLocale();
        }
    }
    
    public function getRootMenus()
    {
        
        $menuRepo = $this->em->getRepository('SKCMSCoreBundle:MenuElement');
//        $menuRepo->setDefaultLocale($this->locale);
        $menus = $menuRepo->findRootMenu();
        
        return $menus;
    }
    
    public function getRootMenusFull($locale = null,$multilingue = true)
    {
        
        if (null !== $locale)
        {
            $this->locale = $locale;
            //dump($this->locale);
        }
        
        $menus = $this->getRootMenus();
        $menus = $this->fullifyMenu($menus,$multilingue);
        
        return $menus;
    }
    
    public function getMenu($menuName,$multilingue = true)
    {
        $menuRepo = $this->em->getRepository('SKCMSCoreBundle:MenuElement');
//        $menuRepo->setDefaultLocale($this->locale);
        $menu = $menuRepo->findOneBy(['textId'=>$menuName],null,null,$this->locale);
        $menu = $this->getTranslatedTree($menu);
        $router = $this->container->get('router');
        $menu = $this->getMenuTargetEntities($menu,$router,$multilingue);
//        die();
        return $menu;
    }
    public function getMenuById($menuId)
    {
//       die($this->locale);
        $menuRepo = $this->em->getRepository('SKCMSCoreBundle:MenuElement');
//        $menuRepo->setDefaultLocale($this->locale);
        $menu = $menuRepo->findMenu($menuId,$this->locale);
        $menu = $this->getTranslatedTree($menu);
        return $menu;
    }
    
    /* Comment supprimer cette merde ? */
    private function getTranslatedTree($menu)
    {
        $locale = $this->locale;

        
        foreach ($menu->getChildren() as $child)
        {
            $child->setTranslatableLocale($locale);
            $this->em->refresh($child);

            $child = $this->getTranslatedTree($child,$locale);
        }
        return $menu;
    }
    
    
    public function getPageList(SKBasePage $page)
    {
        $entitiesParams = $this->container->getParameter('skcms_admin.entities');
        
        
        $lists = [];
        
        foreach ($page->getLists() as $list)
        {
            
            $entityParams = $entitiesParams[$list->getEntity()];
            $repo = $this->em->getRepository($entityParams['class']);
            $entities = $repo->findBy([],[$list->getOrderBy()=>$list->getOrder()],$list->getLimit());
            $lists[$list->getName()] = $entities;
            
        }
        
        return $lists;
        
        
    }
    
    private function fullifyMenu($menus,$multilingue)
    {
        //dump($this->locale);
        $toReturn = [];
        $entitiesParams = $this->container->getParameter('skcms_admin.entities');
//        $pageParam = $entitiesParams['Page'];
        
//        $repo->setDefaultLocale($this->locale);
        
        $router = $this->container->get('router');

        foreach ($menus as $menu)
        {
            $menu = $this->getTranslatedTree($menu);
            $menu = $this->getMenuTargetEntities($menu,$router,$multilingue);
            $toReturn[] = $menu;
        }

        return $toReturn;
        
        
    }
    
    private function getMenuTargetEntities(\SKCMS\CoreBundle\Entity\MenuElement $menu,  \Symfony\Component\Routing\Router $router,$multilingue=true)
    {

        if ($menu->getEntityId() != null )
        {
            $repo = $this->em->getRepository($menu->getEntityClass());
//            $repo->setDefaultLocale($this->locale);
            $targetEntity = $repo->find($menu->getEntityId(),$this->locale);

            
            if (null !== $targetEntity)
            {
                $targetEntity->setTranslatableLocale($this->locale);
                $this->em->refresh($targetEntity);
//                 //dump($router->generate('skcms_front_page_multilingue', ['id'=>$targetEntity->getId(),'slug'=>$targetEntity->getSlug(),'_locale'=>$this->locale]));    
//                 //dump($targetEntity->getSlug());
                
                $menu->setTargetEntity($targetEntity);
                if ($targetEntity instanceof SKBasePage)
                {
                    
                    
                    if ($multilingue)
                    {
                        $path = $targetEntity->getSlug() == 'home' ? $router->generate('skcms_front_home_multilingue', ['_locale'=>$this->locale]) : $router->generate('skcms_front_page_multilingue', ['slug'=>$targetEntity->getSlug(),'_locale'=>$this->locale]);
                        $menu->setLink($path);
                    }
                    else
                    {
                        $path = $targetEntity->getSlug() == 'home' ? $router->generate('skcms_front_home') : $router->generate('skcms_front_page', ['slug'=>$targetEntity->getSlug()]);
                        $menu->setLink($path);
                    }
                    
                }
                else
                {
                    if ($multilingue)
                    {
                        $menu->setLink($router->generate('skcms_front_entity_multilingue', ['slug'=>$targetEntity->getSlug(),'_locale'=>$this->locale,'_format'=>'html']));
                    }
                    else
                    {
                        $menu->setLink($router->generate('skcms_front_entity', ['slug'=>$targetEntity->getSlug(),'_format'=>'html']));
                    }
                }
                
            }
            
            
            
        }
        foreach ($menu->getChildren() as $child)
            {
                $child = $this->getMenuTargetEntities($child,  $router,$multilingue);
            }
        
        return $menu;
    }
}
