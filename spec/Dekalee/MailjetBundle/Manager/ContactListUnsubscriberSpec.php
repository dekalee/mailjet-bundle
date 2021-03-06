<?php

namespace spec\Dekalee\MailjetBundle\Manager;

use Dekalee\MailjetBundle\Convertor\ContactListConvertor;
use Dekalee\MailjetBundle\Manager\ContactListUnsubscriber;
use Mailjet\Client;
use Mailjet\Resources;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ContactListUnsubscriberSpec extends ObjectBehavior
{
    function let(ContactListConvertor $convertor, Client $client)
    {
        $this->beConstructedWith($convertor, $client);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(ContactListUnsubscriber::CLASS);
    }

    function it_should_unsubscribe_user_from_contact_list(ContactListConvertor $convertor, Client $client)
    {
        $convertor->convert(Argument::any())->willReturn(10);

        $this->unsubscribe('list_name', 'foo@bar.com');

        $client->post(Resources::$ContactslistManagecontact, ['id' => 10, 'body' => [
            'Action' => 'unsub',
            'Email' => 'foo@bar.com',
        ]])->shouldBeCalled();
    }
}
