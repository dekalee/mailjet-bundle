<?php

namespace Dekalee\MailjetBundle\DataCollector;

use Dekalee\MailjetBundle\Client\DekaleeClient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\DataCollector\DataCollector;

/**
 * Class MailjetDataCollector
 */
class MailjetDataCollector extends DataCollector
{
    protected $client;

    /**
     * @param DekaleeClient $client
     */
    public function __construct(DekaleeClient $client)
    {
        $this->client = $client;
    }

    /**
     * Collects data for the given Request and Response.
     *
     * @param Request    $request   A Request instance
     * @param Response   $response  A Response instance
     * @param \Exception $exception An Exception instance
     */
    public function collect(Request $request, Response $response, \Exception $exception = null)
    {
        $this->data = $this->client->getCalls();
    }

    /**
     * Returns the name of the collector.
     *
     * @return string The collector name
     */
    public function getName()
    {
        return 'mailjet';
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    public function getCallCount()
    {
        return count($this->data);
    }

    /**
     * Reset the collector to initial state
     */
    public function reset()
    {
        $this->data = array();
    }
}
