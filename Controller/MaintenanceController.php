<?php

namespace SKCMS\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MaintenanceController extends Controller
{
    public function maintenanceAction()
    {
        $event = new \SKCMS\FrontBundle\Event\PreRenderEvent($this->get('request'));
        $this->get('event_dispatcher')
            ->dispatch(\SKCMS\FrontBundle\Event\SKCMSFrontEvents::PRE_RENDER, $event);
        return $this->render('SKCMSCoreBundle:maintenance:maintenance.html.twig');
    }
}
