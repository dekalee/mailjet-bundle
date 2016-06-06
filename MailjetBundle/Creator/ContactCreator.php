<?php

namespace Dekalee\MailjetBundle\Creator;

use Doctrine\Common\Inflector\Inflector;
use Mailjet\Client;
use Mailjet\Resources;

/**
 * Class ContactCreator
 */
class ContactCreator
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
     * @param string $listName
     * @param string $contact
     * @param array  $parameters
     */
    public function create($listName, $contact, array $parameters = [])
    {
        $body = [
            'Email' => $contact
        ];
        $this->client->post(Resources::$Contact, ['body' => $body]);

        $name = Inflector::classify($listName);
        $data = [];
        foreach (['content', 'subject'] as $item) {
            if (array_key_exists($item, $parameters)) {
                $body = [
                    'Datatype' => "str",
                    'Name' => $item . "_" . $name,
                    'NameSpace' => "static"
                ];
                $this->client->post(Resources::$Contactmetadata, ['body' => $body]);
                $data['Data'][] = [
                    'Name' => $item . "_" . $name,
                    'value' => substr($parameters[$item], 0, 1000),
                ];
            }
        }

        if (!empty($data)) {
            $this->client->put(Resources::$Contactdata, ['id' => $contact, 'body' => $data]);
        }
    }

    /**
     * @param int    $contactListId
     * @param string $contact
     */
    public function addToList($contactListId, $contact)
    {
        $body = [
            'ContactsLists' => [
                [
                    'ListID' => $contactListId,
                    'Action' => "addforce"
                ]
            ]
        ];
        $this->client->post(Resources::$ContactManagecontactslists, ['id' => $contact, 'body' => $body]);
    }
}
