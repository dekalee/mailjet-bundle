<?php

namespace Dekalee\MailjetBundle\Client;

use Mailjet\Client;
use Mailjet\Response;

/**
 * Class DekaleeClient
 */
class DekaleeClient extends Client
{
    protected $calls = [];

    /**
     * Trigger a POST request
     *
     * @param array $resource Mailjet Resource/Action pair
     * @param array $args     Request arguments
     *
     * @return Response
     */
    public function post($resource, $args = [])
    {
        $this->calls['POST'][] = ['resource' => $resource, 'args' => $args];

        return parent::post($resource, $args);
    }

    /**
     * Trigger a GET request
     *
     * @param array $resource Mailjet Resource/Action pair
     * @param array $args     Request arguments
     *
     * @return Response
     */
    public function get($resource, $args = [])
    {
        $this->calls['GET'][] = ['resource' => $resource, 'args' => $args];

        return parent::get($resource, $args);
    }

    /**
     * Trigger a POST request
     *
     * @param array $resource Mailjet Resource/Action pair
     * @param array $args     Request arguments
     *
     * @return Response
     */
    public function put($resource, $args = [])
    {
        $this->calls['PUT'][] = ['resource' => $resource, 'args' => $args];


        return parent::put($resource, $args);
    }

    /**
     * Trigger a GET request
     *
     * @param array $resource Mailjet Resource/Action pair
     * @param array $args     Request arguments
     *
     * @return Response
     */
    public function delete($resource, $args = [])
    {
        $this->calls['DELETE'][] = ['resource' => $resource, 'args' => $args];

        return parent::delete($resource, $args);
    }

    /**
     * @return array
     */
    public function getCalls()
    {
        return $this->calls;
    }
}
