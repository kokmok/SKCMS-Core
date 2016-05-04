<?php

namespace SKCMS\CoreBundle\Service;

use Doctrine\ORM\EntityRepository;
use SKCMS\CoreBundle\Entity\SKBasePage;
use Symfony\Component\HttpFoundation\Request;

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
        $modules = $this->container->getParameter('skcms_admin.modules');
        if ($this->container->isScopeActive('request'))
        {
            $locale = $this->container->get('request')->getLocale();
        
            
            $lists = [];
            /**
             * @var $repo EntityRepository
             */
            foreach ($page->getLists() as $list)
            {
                if (array_key_exists($list->getEntity(),$entitiesParams)){
                    $entityParams = $entitiesParams[$list->getEntity()];
                    $repo = $this->em->getRepository($entityParams['class']);
                }
                elseif($modules['blog']['enabled'] && $list->getEntity() === 'BlogPost'){
                    $repo = $this->em->getRepository('SKCMSBlogBundle:BlogPost');
                }

    //            $repo->setDefaultLocale($locale);

                $offset = null;
                /**
                 * @var $request Request
                 */
                $request = $this->container->get('request');
                $routeParams= $request->get('_route_params');
                if(array_key_exists('page',$routeParams)){
                    $offset= ($routeParams['page'] - 1)*$list->getLimit() ;

                }

                
                $entities = $repo->findBy([],[$list->getOrderBy()=>$list->getOrder()],$list->getLimit(),$offset,$locale);
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
                $nombreEntities = $repo->count();
                $nombreEntities = $nombreEntities[0][1];

                $lists[$list->getName()]['entities'] = $entities;
                $lists[$list->getName()]['maxPage'] = ceil($nombreEntities/$list->getLimit());


            }

            return $lists;
        
        }
    }
}
