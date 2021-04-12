<?php /** @noinspection PhpMissingReturnTypeInspection */

/**
 * Project: Email Signature
 *
 * @copyright 2019 Fabian Bitter
 * @author Fabian Bitter (fabian@bitter.de)
 * @version X.X.X
 */

namespace Bitter\EmailSignature\Mail;

use Concrete\Core\Mail\Service as CoreMailService;
use Bitter\EmailSignature\Settings;
use Exception;

class Service extends CoreMailService
{

    /**
     * Sends the email.
     *
     * @param bool $resetData Whether or not to reset the service to its default when this method is done
     *
     * @return bool Returns true upon success, or false if the delivery fails and if the service is not in "testing" state and throwOnFailure is false
     * @throws Exception Throws an exception if the delivery fails and if the service is in "testing" state or throwOnFailure is true
     *
     */
    public function sendMail($resetData = true)
    {
        /** @var $settings Settings */
        $settings = $this->app->make(Settings::class);

        // Save original body
        $body = $this->body;
        $bodyHTML = $this->bodyHTML;

        // Append signature to body
        $this->body = $this->body . strip_tags($settings->getSignature());

        if (strlen($this->bodyHTML) > 0) {
            $this->bodyHTML = $this->bodyHTML . $settings->getSignature();
        }

        // Send the email
        $retVal = parent::sendMail($resetData);

        if (!empty($resetData)) {
            // Restore original body
            $this->body = $body;
            $this->bodyHTML = $bodyHTML;
        }

        return $retVal;
    }

}