<?php

namespace Dekalee\MailjetBundle\Guesser;

use Swift_Mime_Message;

/**
 * Interface TemplateIdGuesserInterface
 */
interface TemplateIdGuesserInterface
{
    /**
     * @param Swift_Mime_Message $message
     *
     * @return bool
     */
    public function supports(Swift_Mime_Message $message);

    /**
     * @param Swift_Mime_Message $message
     *
     * @return string|int
     */
    public function guess(Swift_Mime_Message $message);
}
