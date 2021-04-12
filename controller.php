<?php /** @noinspection PhpMissingReturnTypeInspection */

/**
 * Project: Email Signature
 *
 * @copyright 2019 Fabian Bitter
 * @author Fabian Bitter (fabian@bitter.de)
 * @version X.X.X
 */

namespace Concrete\Package\EmailSignature;

use Bitter\EmailSignature\Provider\ServiceProvider;
use Concrete\Core\Package\Package;

class Controller extends Package
{
    protected $pkgHandle = 'email_signature';
    protected $pkgVersion = '1.2.0';
    protected $appVersionRequired = '8.0.0';
    protected $pkgAutoloaderRegistries = [
        'src/Bitter/EmailSignature' => 'Bitter\EmailSignature',
    ];

    public function getPackageName()
    {
        return t('Email Signature');
    }

    public function getPackageDescription()
    {
        return t('Allows to add signatures to outgoing emails sent by concrete5 mail service.');
    }

    public function on_start()
    {
        /** @var $serviceProvider ServiceProvider */
        $serviceProvider = $this->app->make(ServiceProvider::class);
        $serviceProvider->register();
    }

    public function install()
    {
        parent::install();
        $this->installContentFile('install.xml');
    }

    public function upgrade()
    {
        parent::upgrade();
        $this->installContentFile('install.xml');
    }
}
