<?php

namespace Dekalee\MailjetBundle\Manager;

use Dekalee\MailjetBundle\Convertor\ContactListConvertor;
use Dekalee\MailjetBundle\Creator\ContactCreator;

/**
 * Class ContactListSubscriber
 */
class ContactListSubscriber
{
    protected $contactCreator;
    protected $contactListConvertor;

    /**
     * @param ContactListConvertor $contactListConvertor
     * @param ContactCreator       $contactCreator
     */
    public function __construct(
        ContactListConvertor $contactListConvertor,
        ContactCreator $contactCreator
    ) {
        $this->contactCreator = $contactCreator;
        $this->contactListConvertor = $contactListConvertor;
    }

    /**
     * @param string $name
     * @param string $contact
     * @param array  $parameters
     *
     * @throws \Dekalee\MailjetBundle\Exception\ContactListNotCreated
     */
    public function subscribe($name, $contact, array $parameters = [])
    {
        $contactListId = $this->contactListConvertor->convert($name);
        $this->contactCreator->create($name, $contact, $parameters);
        $this->contactCreator->addToList($contactListId, $contact);
    }
}
