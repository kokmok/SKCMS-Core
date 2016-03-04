<?php
/**
 * Created by jona on 4/03/16
 */

namespace SKCMS\CoreBundle\Listener;


use CH\CoreBundle\Entity\Page;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;

class DeletedEntityFixMenu
{
    public function preRemove(LifecycleEventArgs $args){
        $entity = $args->getObject();

        if ($entity instanceof Page) {


            $menuElements = $args->getObjectManager()->getRepository('SKCMSCoreBundle:MenuElement')->findBy(['entityClass' => '\\'.get_class($entity), 'entityId' => $entity->getId()]);

            foreach($menuElements as $menuElement){
                $args->getObjectManager()->remove($menuElement);
            }
        }

    }

}