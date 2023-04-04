<?php

/**
 * Project: Email Signature
 *
 * @copyright 2019 Fabian Bitter
 * @author Fabian Bitter (fabian@bitter.de)
 * @version X.X.X
 */

namespace Bitter\EmailSignature;

use Concrete\Core\Http\ResponseFactory;
use Concrete\Core\Package\PackageService;
use Concrete\Core\Routing\RouteListInterface;
use Concrete\Core\Routing\Router;
use Concrete\Core\Support\Facade\Application;
use Concrete\Package\EmailSignature\Controller;

class RouteList implements RouteListInterface
{
    public function loadRoutes(Router $router)
    {
        $router
            ->buildGroup()
            ->setNamespace('Concrete\Package\EmailSignature\Controller\Dialog\Support')
            ->setPrefix('/ccm/system/dialogs/email_signature')
            ->routes('dialogs/support.php', 'email_signature');

        $app = Application::getFacadeApplication();
        /** @var $responseFactory ResponseFactory */
        $responseFactory = $app->make(ResponseFactory::class);
        /** @var PackageService $packageService */
        $packageService = $app->make(PackageService::class);
        $packageEntity = $packageService->getByHandle("email_signature");
        /** @var Controller $pkg */
        $pkg = $packageEntity->getController();

        /** @noinspection PhpDeprecationInspection */
        $router->register("/bitter/email_signature/reminder/hide", function () use ($pkg, $responseFactory, $app) {
            $pkg->getConfig()->save('reminder.hide', true);
            $responseFactory->create("")->send();
            $app->shutdown();
        });

        /** @noinspection PhpDeprecationInspection */
        $router->register("/bitter/email_signature/did_you_know/hide", function () use ($pkg, $responseFactory, $app) {
            $pkg->getConfig()->save('did_you_know.hide', true);
            $responseFactory->create("")->send();
            $app->shutdown();
        });

        /** @noinspection PhpDeprecationInspection */
        $router->register("/bitter/email_signature/license_check/hide", function () use ($pkg, $responseFactory, $app) {
            $pkg->getConfig()->save('license_check.hide', true);
            $responseFactory->create("")->send();
            $app->shutdown();
        });
    }
}