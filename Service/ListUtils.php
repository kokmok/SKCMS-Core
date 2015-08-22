<?php

namespace SKCMS\CoreBundle\Service;

use SKCMS\CoreBundle\Entity\SKBasePage;
/**
 * Description of ListUtils
 *
 * @author Jona
 */
class ListUtils 
{

    private $container;
    private $em;
    
    public function __construct($container)
    {
        $this->container = $container;
        $this->em = $container->get('doctrine')->getManager();
    }
    
    
    public function getPageList(SKBasePage $page)
    {
        $entitiesParams = $this->container->getParameter('skcms_admin.entities');
        if ($this->container->isScopeActive('request'))
        {
            $locale = $this->container->get('request')->getLocale();
        
            
            $lists = [];

            foreach ($page->getLists() as $list)
            {
                
                $entityParams = $entitiesParams[$list->getEntity()];
                $repo = $this->em->getRepository($entityParams['bundle'].'Bundle:'.$list->getEntity());
    //            $repo->setDefaultLocale($locale);
                
                $entities = $repo->findBy([],[$list->getOrderBy()=>$list->getOrder()],$list->getLimit(),null,$locale);
                if ($list->getOrderBy() == 'RANDOM')
                {
                    $result = [];
                    foreach ($entities as $entity)
                    {
                        $result[] = $entity[0];
                    }
                    $entities = $result;
                }
    //            
                $lists[$list->getName()] = $entities;

            }

            return $lists;
        
        }
    }
}
