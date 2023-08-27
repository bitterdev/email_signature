<?php /** @noinspection PhpUnused */

/**
 * Project: Email Signature
 *
 * @copyright 2019 Fabian Bitter
 * @author Fabian Bitter (fabian@bitter.de)
 * @version X.X.X
 */

namespace Concrete\Package\EmailSignature\Controller\SinglePage\Dashboard;

use Bitter\EmailSignature\Settings;
use Concrete\Core\Page\Controller\DashboardSitePageController;

class EmailSignature extends DashboardSitePageController
{
    public function view()
    {
        /** @var $settings Settings */
        $settings = $this->app->make(Settings::class);

        if ($this->token->validate("save_signature")) {
            $signatures = json_decode($this->request->request->get("signatures", '{}'), true);

            $settings->setSignatures($signatures);

            $this->set("success", t("Signature successfully saved."));
        }

        $locales = [];

        $site = $this->getSite();

        foreach ($site->getLocales() as $locale) {
            $locales[$locale->getLocale()] = $locale->getLanguageText();
        }

        $this->set('locales', $locales);
        $this->set("signatures", $settings->getSignatures());
    }

}