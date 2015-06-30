<?php
namespace SKCMS\CoreBundle\Manager;

use Symfony\Component\DependencyInjection\ContainerInterface;

use Doctrine\ORM\EntityManager;

/**
 * Translatable entity manager
 */
class TranslatableManager
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em;

    /**
     * @var EntityRepository
     */
    protected $repository;

    /**
     * @var string Class name
     */
    protected $class;
    
    protected $container;
    


    /**
     * Constructor
     *
     * @param EntityManager $em    Entity manager
     * @param string        $class Class name
     */
    public function __construct(EntityManager $em)
    {
        
        $this->em         = $em;
        
    }
    
    

    /**
     * Sets the repository request default locale
     *
     * @param ContainerInterface|null $container
     * 
     * @throws \InvalidArgumentException if repository is not an instance of TranslatableRepository
     * 
     * Obsolete
     */
    public function setRepositoryLocale($container)
    {
        $this->container = $container;
        $this->processRepositoryLocale();
        
    }
    
    public function setContainer($container)
    {
        $this->container = $container;
    }
    
    public function  processRepositoryLocale()
    {
        
        if (null !== $this->container) {
            if (!$this->repository instanceof \SKCMS\CoreBundle\Repository\TranslatableRepository) {
                dump($this->repository);
                die();
                throw new \InvalidArgumentException('A TranslatableManager needs to be linked with a TranslatableRepository to sets default locale.');
            }

            if ($this->container->isScopeActive('request')) 
            {
//                die('locale'. $this->container->get('request')->getLocale());
                $locale = $this->container->get('request')->getLocale();
                $this->repository->setDefaultLocale($locale);
            }
        }
    }
            
}