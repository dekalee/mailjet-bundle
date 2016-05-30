<?php

namespace spec\Dekalee\MailjetBundle\Convetor;

use Dekalee\MailjetBundle\Convertor\ContactListConvertor;
use Dekalee\MailjetBundle\Entity\ContactList;
use Dekalee\MailjetBundle\Entity\ContactListRepository;
use Dekalee\MailjetBundle\Exception\ContactListNotCreated;
use Doctrine\Common\Persistence\ObjectManager;
use Mailjet\Client;
use Mailjet\Resources;
use Mailjet\Response;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ContactListConvertorSpec extends ObjectBehavior
{
    function let(ContactListRepository $repository, ObjectManager $objectManager, Client $client)
    {
        $this->beConstructedWith($repository, $objectManager, $client);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(ContactListConvertor::CLASS);
    }

    function it_should_create_new_contact_list_if_not_exist(
        ContactListRepository $repository,
        ObjectManager $objectManager,
        Client $client,
        Response $response
    ) {
        $repository->findOneByName(Argument::any())->willReturn(null);
        $client->post(Resources::$Contactslist, ['body' => ['Name' => 'ListName']])->willReturn($response);
        $response->success()->willReturn(true);
        $response->getData()->willReturn([
            [
                'ID' => 10,
                'Name' => 'ListName',
            ]
        ]);

        $objectManager->persist(Argument::type(ContactList::CLASS))->shouldBeCalled();
        $objectManager->flush(Argument::type(ContactList::CLASS))->shouldBeCalled();

        $this->convert('list_name')->shouldBeEqualTo(10);
    }

    function it_should_throw_exception_if_list_not_created(
        ContactListRepository $repository,
        ObjectManager $objectManager,
        Client $client,
        Response $response
    ) {
        $repository->findOneByName(Argument::any())->willReturn(null);
        $client->post(Resources::$Contactslist, ['body' => ['Name' => 'ListName']])->willReturn($response);
        $response->success()->willReturn(false);

        $this->shouldThrow(ContactListNotCreated::CLASS)->duringConvert('list_name');

        $objectManager->persist(Argument::any())->shouldNotBeCalled();
        $objectManager->flush(Argument::any())->shouldNotBeCalled();
    }

    function it_should_return_list_id_if_exists(
        ContactListRepository $repository,
        ContactList $contactList
    ) {
        $contactList->getListId()->willReturn(10);
        $repository->findOneByName(Argument::any())->willReturn($contactList);

        $this->convert('list_name')->shouldBeEqualTo(10);
    }
}
