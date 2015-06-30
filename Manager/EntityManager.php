<?php

namespace SKCMS\CoreBundle\Manager;

use SKCMS\CoreBundle\Manager\TranslatableManager;

/**
 * Post entity manager
 */
class EntityManager extends TranslatableManager
{
    protected $security;
    
    public function setSecurity(\Symfony\Component\Security\Core\SecurityContextInterface $security)
    {
        
        $this->security = $security;
    }
    
    public function prepareRepository($class)
    {
        $this->repository = $this->em->getRepository($class);
        $this->processRepositoryLocale();
        
    }
    
    public function getRepository($class)
    {
        $this->prepareRepository($class);
        $this->setRepositotyDraftStatus();
        
        return $this->repository;
    }
    
    private function setRepositotyDraftStatus()
    {
        if ($this->security->isGranted('ROLE_ADMIN') && $this->repository instanceof \SKCMS\CoreBundle\Repository\SKEntityRepository)
        {
            $this->repository->allowDraftEntities();
        }
        
    }
    
    
}