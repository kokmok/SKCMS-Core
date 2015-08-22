<?php

namespace SKCMS\CoreBundle\Listener;

use SKCMS\CoreBundle\Entity\EntityReference;
use Doctrine\ORM\Event\LifecycleEventArgs;
/**
 * Description of EntityReferenceLoader
 *
 * @author jona
 */
class EntityReferenceLoader 
{
    
    
    
    public function postLoad(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        $entityManager = $args->getEntityManager();

        if ($entity instanceof EntityReference) 
        {
            
            $repo = $entityManager->getRepository($entity->getClassName());
            $targetEntity = $repo->find($entity->getForeignKey());
            $entity->setEntity($targetEntity);
            
        }
    }
}
