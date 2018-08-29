<?php

namespace Dekalee\MailjetBundle\Guesser\Strategy;

use Dekalee\MailjetBundle\Guesser\TemplateIdGuesserInterface;
use Swift_Mime_SimpleMessage;

/**
 * Class SimpleTemplateGuesser
 */
class SimpleTemplateGuesser implements TemplateIdGuesserInterface
{
    protected $class;
    protected $templateId;

    /**
     * @param string $class
     * @param int    $templateId
     */
    public function __construct($class, $templateId)
    {
        $this->class = $class;
        $this->templateId = $templateId;
    }

    /**
     * @param Swift_Mime_SimpleMessage $message
     *
     * @return bool|void
     */
    public function supports(Swift_Mime_SimpleMessage $message)
    {
        return $message instanceof $this->class;
    }

    /**
     * @param Swift_Mime_SimpleMessage $message
     *
     * @return int|string
     */
    public function guess(Swift_Mime_SimpleMessage $message)
    {
        return $this->templateId;
    }

}
