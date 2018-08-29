<?php

namespace Dekalee\MailjetBundle\Guesser;

use Swift_Mime_SimpleMessage;

/**
 * Interface TemplateIdGuesserInterface
 */
interface TemplateIdGuesserInterface
{
    /**
     * @param Swift_Mime_SimpleMessage $message
     *
     * @return bool
     */
    public function supports(Swift_Mime_SimpleMessage $message);

    /**
     * @param Swift_Mime_SimpleMessage $message
     *
     * @return string|int
     */
    public function guess(Swift_Mime_SimpleMessage $message);
}
