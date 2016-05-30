<?php

namespace spec\Dekalee\MailjetBundle\Creator;

use Dekalee\MailjetBundle\Creator\ContactCreator;
use Mailjet\Client;
use Mailjet\Resources;
use PhpSpec\ObjectBehavior;

class ContactCreatorSpec extends ObjectBehavior
{
    function let(Client $client)
    {
        $this->beConstructedWith($client);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(ContactCreator::CLASS);
    }

    function it_should_create_a_contact(Client $client)
    {
        $this->create('list_name', 'foo@bar.com', [
            'content' => 'baz',
            'subject' => 'subject',
        ]);

        $client->post(Resources::$Contact, ['body' => ['Email' => 'foo@bar.com']])->shouldBeCalled();
        $client->post(Resources::$Contactmetadata, ['body' => [
            'Datatype' => "str",
            'Name' => "content_ListName",
            'NameSpace' => "static"
        ]])->shouldBeCalled();
        $client->post(Resources::$Contactmetadata, ['body' => [
            'Datatype' => "str",
            'Name' => "subject_ListName",
            'NameSpace' => "static"
        ]])->shouldBeCalled();
        $client->put(Resources::$Contactdata, ['id' => 'foo@bar.com', 'body' => [
            'Data' => [
                [
                    'Name' => "content_ListName",
                    'value' => 'baz',
                ],
                [
                    'Name' => "subject_ListName",
                    'value' => 'subject',
                ],
            ]
        ]])->shouldBeCalled();
    }

    function it_should_add_to_contact_list(Client $client)
    {
        $this->addToList(12, 'foo@bar.com');

        $client->post(Resources::$ContactManagecontactslists, ['id' => 'foo@bar.com', 'body' => [
            'ContactsLists' => [
                [
                    'ListID' => 12,
                    'Action' => "addforce"
                ]
            ]
        ]]);
    }
}
