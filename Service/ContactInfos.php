<?php

namespace SKCMS\CoreBundle\Service;

use SKCMS\CoreBundle\Entity\SKBasePage;
/**
 * Description of ListUtils
 *
 * @author Jona
 */
class ContactInfos 
{

    private $container;
    private $em;
    
    public function __construct($container)
    {
        $this->container = $container;
        $this->em = $container->get('doctrine')->getManager();
    }
    
    
    public function get($key = null,$locale = null)
    {
        $repo = $this->em->getRepository('SKCMSCoreBundle:ContactInfos');
//        $repo->setDefaultLocale($this->container->get('request')->getLocale());
//        die($this->container->get('request')->getLocale());
        $contactInfo = $repo->findOneOrNullBy([],null,null,$locale);
        
        
        
        
        
        
        if (null === $contactInfo)
        {
            return null;
        }
        else
        {
            if ($this->container->isScopeActive('request'))
            {
                $contactInfo->setTranslatableLocale($this->container->get('request')->getLocale());
                $this->em->refresh($contactInfo);
            }
        }
        if (null === $key)
        {
            return $contactInfo;
        }
        else
        {
            $method = 'get'.ucfirst($key);
            if (method_exists($contactInfo,$method))
            {
                return $contactInfo->$method;
            }
            else
            {
                return null;
            }
            
        }
        
        
    }
}
