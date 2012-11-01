<?php
namespace Btn\AppBundle\Listener;

use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;

/**
 * PreExecute
 *
 * add preExecute functionality to the controllers
 */
class PreExecute
{
    //name from internal symfony documentation
    public function onKernelController(FilterControllerEvent $event) {
        if(HttpKernelInterface::MASTER_REQUEST === $event->getRequestType()) {
            $controllers = $event->getController();
            if(is_array($controllers)) {
                $controller = $controllers[0];
                //call preExecute if exists
                if(is_object($controller) && method_exists($controller, 'preExecute')) {
                    $controller->preExecute();
                }
            }
        }
    }

}