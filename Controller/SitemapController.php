<?php

namespace SKCMS\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function sitemapAction()
    {
        $entitiesParams = $this->get('skcms_admin.entities');
        $entities = [];
        foreach ($entitiesParams as $entityParam){
            $entities = array_merge($entities, $this->getDoctrine()->getManager()->getRepository($entityParam['class'])->findAll());
        }
        return $this->render('SKCMSCoreBundle::sitemap.xml.twig', array('entities' => $entities));
    }
}
