<?php
namespace SKCMS\CoreBundle\Listener;

use Doctrine\ORM\Event\LifecycleEventArgs;
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

    public function preRemove(LifecycleEventArgs $args){
        $entity = $args->getEntity();
        $em = $args->getEntityManager();
        $uow = $em->getUnitOfWork();
        if ($entity instanceof SKBaseEntity){
            $repo = $em->getRepository('SKCMSCoreBundle:Translation\EntityTranslation');
            $translations = $repo->findBy(['foreignKey'=>$entity->getId(),'objectClass'=>get_class($entity)]);
            foreach ($translations as $translation){
                $uow->scheduleForDelete($translation);
            }
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
            if (is_subclass_of($entity, '\SKCMS\CoreBundle\Entity\SKBaseEntity'))
            {
                $entity = $this->checkTranslatedSlug($entity);
               
            }
            
            
            
            
        }
        
        foreach ($uow->getScheduledEntityUpdates() as $entity) 
        {
//            //dump($entity);
//                die();
//            if ($entity instanceof SKBaseEntity)
            if (is_subclass_of($entity, '\SKCMS\CoreBundle\Entity\SKBaseEntity'))
            {
                $entity = $this->checkTranslatedSlug($entity);
                
                
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
//        
//        dump($entity);
//        dump($translationEntity);
//        dump(get_class($entity));
//        dump('\\'.$translationEntity->getObjectClass());
//        die();
        
//        if (null === $translationEntity || ($translationEntity->getForeignKey()==$entity->getId() && '\\'.$translationEntity->getObjectClass() == get_class($entity)))
        if (null === $translationEntity || ($translationEntity->getForeignKey()==$entity->getId() && $translationEntity->getObjectClass() == get_class($entity)))
        {
            if ($loopIndex>0)
            {
                $entity->setSlug($slug);
                $this->em->persist($entity);
                $this->em->flush();
            }
            
            return $entity;
        }
        else
        { 
//            die('loop');
            return $this->checkTranslatedSlug($entity,$loopIndex+1);
        }
        
    }
}
