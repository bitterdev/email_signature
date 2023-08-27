<?php /** @noinspection PhpMissingParamTypeInspection */
/** @noinspection PhpMissingReturnTypeInspection */

/**
 * Project: Email Signature
 *
 * @copyright 2019 Fabian Bitter
 * @author Fabian Bitter (fabian@bitter.de)
 * @version X.X.X
 */

namespace Bitter\EmailSignature;

use Concrete\Core\Application\Application;
use Concrete\Core\Config\Repository\Repository;
use Concrete\Core\Entity\Site\Site;
use Concrete\Core\Localization\Localization;
use Concrete\Core\Site\Service;

class Settings
{
    protected $config;
    protected $app;

    public function __construct(
        Application $app,
        Service $siteService
    )
    {
        $this->app = $app;
        $this->config = $siteService->getActiveSiteForEditing()->getConfigRepository();
    }

    /**
     * @return array
     */
    public function getSignatures()
    {
        return $this->config->get("email_signature.signatures", []);
    }

    /**
     * @param array $signatures
     */
    public function setSignatures($signatures)
    {
        $this->config->save("email_signature.signatures", $signatures);
    }

    /**
     * @param mixed|string $locale
     * @return mixed|string
     */
    public function getSignature($locale = null, $site = null)
    {
        $signatures = $this->getSignatures();

        if (is_null($locale) || !is_string($locale)) {
            $locale = Localization::activeLocale();
        }

        if (is_array($signatures) && isset($signatures[$locale])) {
            return $signatures[$locale];
        } else {
            if (!$site instanceof Site) {
                /** @var $site Site */
                $site = $this->app->make('site')->getActiveSiteForEditing();
            }

            $defaultLocale = $site->getDefaultLocale();

            if (is_object($defaultLocale)) {
                if (isset($signatures[$defaultLocale->getLocale()])) {
                    return $signatures[$defaultLocale->getLocale()];
                } else {
                    return '';
                }
            } else {
                return '';
            }
        }
    }

}