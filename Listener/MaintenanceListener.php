<?php
/**
 * Created by jona on 27/05/16
 */

namespace SKCMS\CoreBundle\Listener;

use SKCMS\CoreBundle\Controller\MaintenanceController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

class MaintenanceListener
{
    private $maintenance;
    private $container;
    private $twig;
    private $isAdmin;
    private $routesExclusion = [];



    public function setContainer(ContainerInterface $container){
        $this->maintenance = $container->getParameter('skcms_admin.siteinfo')['maintenance']['enabled'];
        $this->routesExclusion= $container->getParameter('skcms_admin.siteinfo')['maintenance']['routes_exclusion'];
        $this->twig = $container->get('twig');

        $this->container = $container;
//        dump($this->container->get('security.token_storage')->getToken());
//        die();
    }

    private function customRoutesExclusion(){
        $customRoutes = [
            '#^_wdt#',
            '#^fos_user_security#',
            '#^skcms.admin#',
            '#^sk_admin#'

        ];
        $this->routesExclusion = array_merge($this->routesExclusion,$customRoutes);
    }

    public function onKernelController(FilterControllerEvent $event){

        if (!$event->isMasterRequest()){
            return;
        }
        if (!$this->maintenance){
            return;
        }


        $this->customRoutesExclusion();
        foreach ($this->routesExclusion as $routePattern){
            if (preg_match($routePattern,$event->getRequest()->get('_route'))){
                return;
            }
        }


        $this->isAdmin = $this->container->get('security.authorization_checker')->isGranted('ROLE_ADMIN');

        if (!$this->isAdmin){
            $controller = new MaintenanceController();
            $controller->setContainer($this->container);
            $event->setController([$controller,'maintenanceAction']);
//            dump($event->getController());
//            die();

        }
    }
}