<?php

namespace Dekalee\MailjetBundle\Convertor;

use Dekalee\MailjetBundle\Entity\ContactList;
use Dekalee\MailjetBundle\Repository\ContactListRepository;
use Dekalee\MailjetBundle\Exception\ContactListNotCreated;
use Doctrine\Common\Inflector\Inflector;
use Doctrine\Common\Persistence\ObjectManager;
use Mailjet\Client;
use Mailjet\Resources;

/**
 * Class ContactListConvertor
 */
class ContactListConvertor
{
    protected $client;
    protected $repository;
    protected $objectManager;

    /**
     * @param ContactListRepository $repository
     * @param ObjectManager         $objectManager
     * @param Client                $client
     */
    public function __construct(ContactListRepository $repository, ObjectManager $objectManager, Client $client)
    {
        $this->client = $client;
        $this->repository = $repository;
        $this->objectManager = $objectManager;
    }

    /**
     * @param string $name
     *
     * @return int
     * @throws ContactListNotCreated
     */
    public function convert($name)
    {
        $name = Inflector::classify($name);
        if (($contactList = $this->repository->findOneByName($name)) instanceof ContactList) {
            return $contactList->getListId();
        }

        $body = [
            'Name' => $name,
        ];
        $response = $this->client->post(Resources::$Contactslist, ['body' => $body]);

        if (!$response->success()) {
            throw new ContactListNotCreated();
        }

        $contactList = new ContactList();
        $contactList->setName($name);
        $contactList->setListId($response->getData()[0]['ID']);

        $this->objectManager->persist($contactList);
        $this->objectManager->flush($contactList);

        return $contactList->getListId();
    }
}
