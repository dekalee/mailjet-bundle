<?php

namespace Dekalee\MailjetBundle\Creator;

use Dekalee\MailjetBundle\Convertor\ContactListConvertor;

/**
 * Class ContactListCreator
 */
class ContactListCreator
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
    public function addContactToList($name, $contact, array $parameters = [])
    {
        $contactListId = $this->contactListConvertor->convert($name);
        $this->contactCreator->create($name, $contact, $parameters);
        $this->contactCreator->addToList($name, $contactListId);
    }
}
