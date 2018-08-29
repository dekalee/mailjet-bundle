<?php

namespace Dekalee\MailjetBundle\Transport;

use Dekalee\MailjetBundle\Guesser\TemplateIdGuesserManager;
use Dekalee\Message\SwiftCustomVarsMessage;
use Mailjet\Client;
use Mailjet\Resources;
use Swift_Events_EventListener;
use Swift_Mime_Message;

/**
 * Class MailjetSendApiTransport
 */
class MailjetSendApiTransport implements \Swift_Transport
{
    protected $client;
    protected $dispatcher;
    protected $started = true;
    protected $templateIdGuesserManager;

    /**
     * @param Client                        $client
     * @param \Swift_Events_EventDispatcher $dispatcher
     * @param TemplateIdGuesserManager      $templateIdGuesserManager
     */
    public function __construct(
        Client $client,
        \Swift_Events_EventDispatcher $dispatcher,
        TemplateIdGuesserManager $templateIdGuesserManager
    ) {
        $this->client = $client;
        $this->dispatcher = $dispatcher;
        $this->templateIdGuesserManager = $templateIdGuesserManager;
    }

    /**
     * Test if this Transport mechanism has started.
     *
     * @return bool
     */
    public function isStarted()
    {
        return $this->started;
    }

    /**
     * Start this Transport mechanism.
     */
    public function start()
    {
        $this->started = true;
    }

    /**
     * Stop this Transport mechanism.
     */
    public function stop()
    {
        $this->started = false;
    }

    /**
     * Send the given Message.
     *
     * Recipient/sender data will be retrieved from the Message API.
     * The return value is the number of recipients who were accepted for delivery.
     *
     * @param Swift_Mime_Message $message
     * @param string[]           $failedRecipients An array of failures by-reference
     *
     * @return int
     */
    public function send(Swift_Mime_Message $message, &$failedRecipients = null)
    {
        $failedRecipients = (array) $failedRecipients;

        if ($evt = $this->dispatcher->createSendEvent($this, $message)) {
            $this->dispatcher->dispatchEvent($evt, 'beforeSendPerformed');
            if ($evt->bubbleCancelled()) {
                return 0;
            }
        }

        $from = $message->getFrom();
        $recipients = [];
        $headers = [];

        foreach ($message->getTo() as $email => $name) {
            $recipients[] = ['Email' => $email, 'Name' => $email];
        }

        foreach ($message->getHeaders()->getAll() as $header) {
            $headers[$header->getFieldName()] = $header->getFieldBody();
        }

        $vars = ['content' => $message->getBody()];
        if ($message instanceof SwiftCustomVarsMessage) {
            $vars = $message->getVars();
        }

        $body = [
            'FromEmail' => key($from),
            'Subject' => $message->getSubject(),
            'Vars' => $vars,
            'Recipients' => $recipients,
            'MJ-TemplateID' => $this->templateIdGuesserManager->guess($message),
            'MJ-TemplateLanguage' => 'True',
            'Headers' => $headers,
        ];
        
        if (null !== $fromName = current($from)) {
            $body['FromName'] = $fromName;
        }

        foreach ($message->getChildren() as $child) {
            if ($child instanceof \Swift_Attachment) {
                $body['Attachments'][] = [
                    'Content-type' => $child->getContentType(),
                    'Filename' => $child->getFilename(),
                    'content' => base64_encode($child->getBody())
                ];
            }
        }

        $response = $this->client->post(Resources::$Email, ['body' => $body]);
        $success = $response->success();

        if ($evt) {
            $evt->setResult($success ? \Swift_Events_SendEvent::RESULT_SUCCESS : \Swift_Events_SendEvent::RESULT_FAILED);
            $this->dispatcher->dispatchEvent($evt, 'sendPerformed');
        }

        if ($success) {
            return count((array) $message->getTo());
        }

        return 0;
    }

    /**
     * Register a plugin in the Transport.
     *
     * @param Swift_Events_EventListener $plugin
     */
    public function registerPlugin(Swift_Events_EventListener $plugin)
    {
        $this->dispatcher->bindEventListener($plugin);
    }

    /**
     * @return bool
     */
    public function ping()
    {
        return true;
    }
}
