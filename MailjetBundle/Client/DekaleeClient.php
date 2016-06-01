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
        $response = parent::post($resource, $args);

        $this->calls[] = [
            'method' => 'POST',
            'resource' => $resource,
            'args' => $args,
            'success' => $response->success(),
            'response' => $response->getBody(),
        ];

        return $response;
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
        $response = parent::get($resource, $args);

        $this->calls[] = [
            'method' => 'GET',
            'resource' => $resource,
            'args' => $args,
            'success' => $response->success(),
            'response' => $response->getBody(),
        ];

        return $response;
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
        $response = parent::put($resource, $args);

        $this->calls[] = [
            'method' => 'PUT',
            'resource' => $resource,
            'args' => $args,
            'success' => $response->success(),
            'response' => $response->getBody(),
        ];

        return $resource;
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
        $response = parent::delete($resource, $args);

        $this->calls[] = [
            'method' => 'DELETE',
            'resource' => $resource,
            'args' => $args,
            'success' => $response->success(),
            'response' => $response->getBody(),
        ];

        return $response;
    }

    /**
     * @return array
     */
    public function getCalls()
    {
        return $this->calls;
    }
}
