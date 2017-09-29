<?php

/**
 * Locale subscriber event
 *
 * @author Tomasz Ngondo <tomasz.ngondo@outlook.fr>
 * @copyright 2017
 */

namespace BZM\CoreBundle\EventSubscriber;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class LocaleSubscriber implements EventSubscriberInterface
{
    private $defaultLocale;

    public function __construct($defaultLocale = 'en') {
        $this->defaultLocale = $defaultLocale;
    }

    public function onKernelRequest(GetResponseEvent $event) {
        $request = $event->getRequest();
        $session = $request->getSession();
        $page    = $request->getPathInfo();
        
        if (!$request->hasPreviousSession()) {
            return;
        }

        // try to see if the locale has been set as a _locale routing parameter
        if ($locale = $request->attributes->get('_locale')) {
            $session->set('_locale', $locale);
            $this->setPageNameToRequest($request, substr($page, 4));
        } else {
            if ($session->get('_locale') == null) { // case logout
                $session->set('_locale', $this->defaultLocale);
            }
            // if no explicit locale has been set on this request, use one from the session
            $request->setLocale($session->get('_locale', $this->defaultLocale));
            $this->setPageNameToRequest($request, trim($page, '/'));
        }
        
        // set page name on exception
        if ($request->attributes->get('exception')) {
            $request->request->set('page', 'error-page');
        }
    }

    public function setPageNameToRequest($request, $page) {
        $request->request->set('page', $page);

        if ($request->request->get('page') == '') {
            $request->request->set('page', 'cover');
        }
    }

    public static function getSubscribedEvents() {
        return array(
            KernelEvents::REQUEST => array(array('onKernelRequest', 15))
        );
    }
}