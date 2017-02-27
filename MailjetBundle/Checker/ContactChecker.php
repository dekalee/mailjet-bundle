<?php

namespace Dekalee\MailjetBundle\Checker;

use Mailjet\Client;

/**
 * Class ContactChecker
 */
class ContactChecker
{
    protected $client;

    /**
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * hasNoBlockedEmail
     *
     * @param $email
     *
     * @return bool
     */
    public function hasNoBlockedEmail($email)
    {
        $response = $this->client->get(['contactstatistics', $email]);
        $blockedCount = $response->getBody();
        if ($response->success() && $blockedCount['Data'][0]['BlockedCount']) {
            return false;
        }
        return true;
    }
}
