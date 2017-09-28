<?php

/**
 * Form success listener
 *
 * @author Tomasz Ngondo <tomasz.ngondo@outlook.fr>
 * @copyright 2017
 */

 namespace BZM\UserBundle\EventListener;
 
 use FOS\UserBundle\FOSUserEvents;
 use FOS\UserBundle\Event\FormEvent;
 use Symfony\Component\EventDispatcher\EventSubscriberInterface;
 use Symfony\Component\HttpFoundation\RedirectResponse;
 use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
 
 /**
  * Listener responsible to change the redirection when a form is successfully filled
  */
 class FormSuccessListener implements EventSubscriberInterface
 {
     private $router;
 
     public function __construct(UrlGeneratorInterface $router)
     {
         $this->router = $router;
     }
 
     /**
      * {@inheritDoc}
      */
     public static function getSubscribedEvents()
     {
         return array(
             FOSUserEvents::REGISTRATION_SUCCESS => array('onFormSuccess',-10),
             FOSUserEvents::CHANGE_PASSWORD_SUCCESS => array('onFormSuccess',-10), 
             FOSUserEvents::PROFILE_EDIT_SUCCESS => array('onFormSuccess',-10),                        
         );
     }
 
     public function onFormSuccess(FormEvent $event)
     {
        $url = $this->router->generate('sonata_admin_dashboard');
        
        $event->setResponse(new RedirectResponse($url));
     }
 }