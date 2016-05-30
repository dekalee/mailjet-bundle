<?php

namespace Dekalee\MailjetBundle\Creator;

use Dekalee\MailjetBundle\Convertor\ContactListConvertor;
use Doctrine\Common\Inflector\Inflector;
use Mailjet\Client;
use Mailjet\Resources;

/**
 * Class ContactListCreator
 */
class ContactListCreator
{
    protected $client;
    protected $contactListConvertor;

    /**
     * @param ContactListConvertor $contactListConvertor
     * @param Client               $client
     */
    public function __construct(ContactListConvertor $contactListConvertor, Client $client)
    {
        $this->client = $client;
        $this->contactListConvertor = $contactListConvertor;
    }

    /**
     * @param string $name
     * @param string $contact
     * @param array  $parameters
     *
     * @throws \Dekalee\MailjetBundle\Exception\ContactListNotCreated
     */
    public function addContactToList($name, $contact, array $parameters = [])
    {
        $contactListId = $this->contactListConvertor->convert($name);

        $body = [
            'Email' => $contact
        ];

        $response = $this->client->post(Resources::$Contact, ['body' => $body]);
        var_dump($response->success());
        print_r($response->getData());


        $body = [
            'Datatype' => "str",
            'Name' => "content_" . $name,
            'NameSpace' => "static"
        ];
        $response = $this->client->post(Resources::$Contactmetadata, ['body' => $body]);
        var_dump($response->success());
        print_r($response->getData());

        $body = [
            'Data' => [
                [
                    'Name' => "content_" . $name,
                    'value' => $parameters['content']
                ],
            ]
        ];
        $response = $this->client->put(Resources::$Contactdata, ['id' => $contact, 'body' => $body]);
        var_dump($response->success());
        print_r($response->getData());

        $body = [
            'ContactsLists' => [
                [
                    'ListID' => $contactListId,
                    'Action' => "addforce"
                ]
            ]
        ];
        $response = $this->client->post(Resources::$ContactManagecontactslists, ['id' => $contact, 'body' => $body]);
        var_dump($response->success());
        print_r($response->getData());
    }
}
