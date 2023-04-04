<?php

/**
 * Project: Email Signature
 *
 * @copyright 2019 Fabian Bitter
 * @author Fabian Bitter (fabian@bitter.de)
 * @version X.X.X
 */


namespace Bitter\EmailSignature\Provider;

use Bitter\EmailSignature\Mail\Service as MailService;
use Concrete\Core\Foundation\Service\Provider;
use Concrete\Core\Mail\Service as CoreMailService;
use Concrete\Core\Routing\Router;
use Bitter\EmailSignature\RouteList;

class ServiceProvider extends Provider
{

    public function register()
    {
        $this->initializeMailService();
        $this->initializeRouter();
    }

    private function initializeMailService()
    {
        foreach (['helper/mail', 'mail', CoreMailService::class] as $abstract) {
            $this->app->extend($abstract, function () {
                return $this->app->make(MailService::class);
            });
        }
    }

    private function initializeRouter()
    {
        /** @var Router $router */
        $router = $this->app->make("router");
        $list = new RouteList();
        $list->loadRoutes($router);
    }
}