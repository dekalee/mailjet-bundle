<?php

namespace Dekalee\MailjetBundle\Guesser;

use Swift_Mime_Message;

/**
 * Class TemplateIdGuesserManager
 */
class TemplateIdGuesserManager
{
    protected $templateId;
    protected $guessers = [];

    /**
     * @param int|string $templateId
     */
    public function __construct($templateId)
    {
        $this->templateId = $templateId;
    }

    /**
     * @param Swift_Mime_Message $message
     *
     * @return string|int|null
     */
    public function guess(Swift_Mime_Message $message)
    {
        /** @var TemplateIdGuesserInterface $guesser */
        foreach ($this->guessers as $guesser) {
            if ($guesser->supports($message)) {
                return $guesser->guess($message);
            }
        }

        return $this->templateId;
    }

    /**
     * @param TemplateIdGuesserInterface $guesser
     */
    public function addGuesser(TemplateIdGuesserInterface $guesser)
    {
        $this->guessers[] = $guesser;
    }
}
