<?php

namespace Dekalee\MailjetBundle\Unsubscriber;

use Dekalee\MailjetBundle\Convertor\ContactListConvertor;
use Mailjet\Client;
use Mailjet\Resources;

/**
 * Class ContactListUnsubscriber
 */
class ContactListUnsubscriber
{
    protected $client;
    protected $convertor;

    /**
     * @param ContactListConvertor $convertor
     * @param Client               $client
     */
    public function __construct(ContactListConvertor $convertor, Client $client)
    {
        $this->client = $client;
        $this-> convertor = $convertor;
    }

    /**
     * @param string $listName
     * @param string $contact
     */
    public function unsubscribe($listName, $contact)
    {
        $contactListId = $this->convertor->convert($listName);
        $this->client->post(Resources::$ContactManagecontactslists, ['id' => $contact, 'body' => [
            'ContactsLists' => [
                'ListId' => $contactListId,
                'Action' => 'unsub',
            ],
        ]]);
    }
}
