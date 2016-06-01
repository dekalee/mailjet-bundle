<?php

namespace spec\Dekalee\MailjetBundle\Manager;

use Dekalee\MailjetBundle\Convertor\ContactListConvertor;
use Dekalee\MailjetBundle\Creator\ContactCreator;
use Dekalee\MailjetBundle\Manager\ContactListSubscriber;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ContactListSubscriberSpec extends ObjectBehavior
{
    function let(
        ContactListConvertor $contactListConvertor,
        ContactCreator $contactCreator
    ) {
        $this->beConstructedWith($contactListConvertor, $contactCreator);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(ContactListSubscriber::CLASS);
    }

    function it_should_subscribe_a_contact_to_a_list(
        ContactListConvertor $contactListConvertor,
        ContactCreator $contactCreator
    ) {
        $contactListConvertor->convert(Argument::any())->willReturn(10);

        $this->subscribe('foo', 'bar@baz.com', []);

        $contactListConvertor->convert('foo')->shouldBeCalled();
        $contactCreator->create('foo', 'bar@baz.com', [])->shouldBeCalled();
        $contactCreator->addToList(10, 'bar@baz.com')->shouldBeCalled();
    }
}
