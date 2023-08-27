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
use Bitter\EmailSignature\RouteList;
use Concrete\Core\Foundation\Service\Provider;
use Concrete\Core\Mail\Service as CoreMailService;
use Concrete\Core\Routing\Router;

class ServiceProvider extends Provider
{

    public function register()
    {
        $this->initializeMailService();
        $this->initializeRoutes();
    }

    private function initializeMailService()
    {
        foreach (['helper/mail', 'mail', CoreMailService::class] as $abstract) {
            $this->app->extend($abstract, function () {
                return $this->app->make(MailService::class);
            });
        }
    }
    private function initializeRoutes()
    {
        /** @var Router $router */
        $router = $this->app->make("router");
        $list = new RouteList();
        $list->loadRoutes($router);
    }
}