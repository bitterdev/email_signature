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

class ServiceProvider extends Provider
{

    public function register()
    {
        $this->initializeMailService();
    }

    private function initializeMailService()
    {
        foreach (['helper/mail', 'mail', CoreMailService::class] as $abstract) {
            $this->app->extend($abstract, function () {
                return $this->app->make(MailService::class);
            });
        }
    }
}