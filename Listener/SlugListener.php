<?php
namespace SKCMS\CoreBundle\Listener;

use Doctrine\ORM\Event\OnFlushEventArgs;
use SKCMS\CoreBundle\Entity\SKBaseEntity;
/**
 * Description of SlugListener
 *
 * @author Jona
 */
class SlugListener 
{
    private $container;
    private $em;
    private $locale;
    
    
    public function __construct($container)
    {
        $this->container = $container;
//        $this->em = $container->get('doctrine')->getManager();
        if ($container->isScopeActive('request'))
        {
            $this->locale = $container->get('request')->getLocale();
        }
        
    }
    
    public function onFlush(OnFlushEventArgs $eventArgs)
    {
        if ($this->locale === null)
        {
            return;
        }
        
        $this->em = $eventArgs->getEntityManager();
        $uow = $this->em->getUnitOfWork();
//        $uow->get
        
        foreach ($uow->getScheduledEntityInsertions() as $entity) 
        {
            if ($entity instanceof SKBaseEntity)
            {
                $entity = $this->checkTranslatedSlug($entity);
            }
            
            
        }
        
        foreach ($uow->getScheduledEntityUpdates() as $entity) 
        {
//            //dump($entity);
//                die();
            if ($entity instanceof SKBaseEntity)
            {
                $entity = $this->checkTranslatedSlug($entity);
                
//                //dump($entity);
//                die();
            }
        }
        
    }
    
    
    private function checkTranslatedSlug($entity,$loopIndex = 0)
    {
        if ($loopIndex >0)
        {
            if (preg_match('#[a-z0-9-](-\d+)$#', $entity->getSlug()))
            {
                $slug = substr($entity->getSlug(),  0,strrpos($entity->getSlug(), '-')) .'-'.$loopIndex;
            }
            else
            {
                $slug = $entity->getSlug().'-'.$loopIndex;
            }

        }
        else
        {
            $slug = $entity->getSlug();
        }
        
        
        
//        //dump($slug);
        
        $repo = $this->em->getRepository('SKCMS\CoreBundle\Entity\Translation\EntityTranslation');
        $translationEntity = $repo->findObjectBySlug($slug,$this->locale);
        
        
        if (null === $translationEntity || ($translationEntity->getForeignKey()==$entity->getId() && '\\'.$translationEntity->getObjectClass() == get_class($entity)))
        {
            
            $entity->setSlug($slug);
           
            return $entity;
        }
        else
        { 
            return $this->checkTranslatedSlug($entity,$loopIndex+1);
        }
        
    }
}
