<?php

namespace SKCMS\CoreBundle\Service;

use SKCMS\CoreBundle\Entity\SKBasePage;
/**
 * Description of ListUtils
 *
 * @author Jona
 */
class SlugUtils 
{

    private $container;
    private $em;
    
    public function __construct($container)
    {
        $this->container = $container;
        $this->em = $container->get('doctrine')->getManager();
    }
    
    
    public function getPageBySlug($slug,$locale = null)
    {
        
        $entitiesParams = $this->container->getParameter('skcms_admin.entities');
        $entityParams = $entitiesParams['Page'];
        
        $skManager = $this->container->get('skcms.corebundle.manager.entity');

        $repo = $this->em->getRepository('SKCMS\CoreBundle\Entity\Translation\EntityTranslation');
        
        
//        $page = $repo->findAllTranslations('slug',$slug,$entityParams['class']);
        $translationEntity = $repo->findObjectByTranslation('slug',$slug,$entityParams['class'],$locale);
        if (null == $translationEntity)
        {
            return null;
        }
        
        $repo = $skManager->getRepository($entityParams['bundle'].'Bundle:Page');
        return $repo->findFull($translationEntity->getForeignKey(),$locale);
        
        
        
        
        
    }
}
