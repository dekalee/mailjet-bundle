<?php

namespace spec\Dekalee\MailjetBundle\Transport;

use Dekalee\MailjetBundle\Guesser\TemplateIdGuesserManager;
use Dekalee\MailjetBundle\Transport\MailjetSendApiTransport;
use Dekalee\MailjetBundle\Message\SwiftCustomVarsMessage;
use Mailjet\Client;
use Mailjet\Resources;
use Mailjet\Response;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Swift_Mime_SimpleMessage;

class MailjetSendApiTransportSpec extends ObjectBehavior
{
    function let(
        Client $client,
        \Swift_Events_EventDispatcher $dispatcher,
        TemplateIdGuesserManager $manager
    ) {
        $this->beConstructedWith($client, $dispatcher, $manager, 'foo@bar.com', true);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(MailjetSendApiTransport::CLASS);
    }

    function it_should_be_a_mailer_transport()
    {
        $this->shouldHaveType(\Swift_Transport::CLASS);
    }

    function it_should_be_started_unless_stopped()
    {
        $this->isStarted()->shouldBeEqualTo(true);
        $this->start();
        $this->isStarted()->shouldBeEqualTo(true);
        $this->stop();
        $this->isStarted()->shouldBeEqualTo(false);
        $this->start();
        $this->isStarted()->shouldBeEqualTo(true);
    }
    
    function it_should_send_mail(
        Swift_Mime_SimpleMessage $message,
        Client $client,
        Response $response,
        TemplateIdGuesserManager $manager,
        \Swift_Events_EventDispatcher $dispatcher,
        \Swift_Events_SendEvent $event,
        \Swift_Mime_SimpleHeaderSet $headers,
        \Swift_Attachment $attachment
    ) {
        $manager->guess($message)->willReturn('foo');
        $message->getFrom()->willReturn(['test@foo.com' => null]);
        $message->getTo()->willReturn(['to@foo.com' => null, 'bar@baz.com' => null]);
        $message->getSubject()->willReturn('Mail subject');
        $message->getBody()->willReturn('Mail body');
        $message->getHeaders()->willReturn($headers);
        $message->getChildren()->willReturn([$attachment]);

        $attachment->getFilename()->willReturn('foo.bar');
        $attachment->getContentType()->willReturn('application/bar');
        $attachment->getBody()->willReturn('baz');
        
        $headers->getAll()->willReturn([]);

        $response->success()->willReturn(true);
        $client->post(Argument::any(), Argument::any())->willReturn($response);

        $dispatcher->createSendEvent(Argument::any(), Argument::any())->willReturn($event);
        $dispatcher->dispatchEvent($event, 'beforeSendPerformed')->shouldBeCalled();
        $event->bubbleCancelled()->willReturn(false);
        $event->setResult(\Swift_Events_SendEvent::RESULT_SUCCESS)->shouldBeCalled();
        $dispatcher->dispatchEvent($event, 'sendPerformed')->shouldBeCalled();

        $this->send($message)->shouldBeEqualTo(2);

        $client->post(Resources::$Email, ['body' => [
            'FromEmail' => 'test@foo.com',
            'Subject' => 'Mail subject',
            'Vars' => ['content' => 'Mail body'],
            'Recipients' => [
                ['Email' => 'to@foo.com', 'Name' => 'to@foo.com'],
                ['Email' => 'bar@baz.com', 'Name' => 'bar@baz.com'],
            ],
            'MJ-TemplateID' => 'foo',
            'MJ-TemplateLanguage' => 'True',
            'Headers' => [
                'MJ-TemplateErrorReporting' => 'foo@bar.com',
                'MJ-TemplateErrorDeliver' => 'deliver',
            ],
            'Attachments' => [
                [
                    'Content-type' => 'application/bar',
                    'Filename' => 'foo.bar',
                    'content' => base64_encode('baz')
                ],
            ],
        ]])->shouldBeCalled();
    }

    function it_should_send_mail_with_custom_vars(
        SwiftCustomVarsMessage $message,
        Client $client,
        Response $response,
        TemplateIdGuesserManager $manager,
        \Swift_Events_EventDispatcher $dispatcher,
        \Swift_Events_SendEvent $event,
        \Swift_Mime_SimpleHeaderSet $headers,
        \Swift_Attachment $attachment
    ) {
        $manager->guess($message)->willReturn('foo');
        $message->getFrom()->willReturn(['test@foo.com' => null]);
        $message->getTo()->willReturn(['to@foo.com' => null, 'bar@baz.com' => null]);
        $message->getSubject()->willReturn('Mail subject');
        $message->getBody()->willReturn('Mail body');
        $message->getVars()->willReturn(['foo' => 'bar']);
        $message->getHeaders()->willReturn($headers);
        $message->getChildren()->willReturn([$attachment]);

        $attachment->getFilename()->willReturn('foo.bar');
        $attachment->getContentType()->willReturn('application/bar');
        $attachment->getBody()->willReturn('baz');

        $headers->getAll()->willReturn([]);

        $response->success()->willReturn(true);
        $client->post(Argument::any(), Argument::any())->willReturn($response);

        $dispatcher->createSendEvent(Argument::any(), Argument::any())->willReturn($event);
        $dispatcher->dispatchEvent($event, 'beforeSendPerformed')->shouldBeCalled();
        $event->bubbleCancelled()->willReturn(false);
        $event->setResult(\Swift_Events_SendEvent::RESULT_SUCCESS)->shouldBeCalled();
        $dispatcher->dispatchEvent($event, 'sendPerformed')->shouldBeCalled();

        $this->send($message)->shouldBeEqualTo(2);

        $client->post(Resources::$Email, ['body' => [
            'FromEmail' => 'test@foo.com',
            'Subject' => 'Mail subject',
            'Vars' => ['foo' => 'bar'],
            'Recipients' => [
                ['Email' => 'to@foo.com', 'Name' => 'to@foo.com'],
                ['Email' => 'bar@baz.com', 'Name' => 'bar@baz.com'],
            ],
            'MJ-TemplateID' => 'foo',
            'MJ-TemplateLanguage' => 'True',
            'Headers' => [
                'MJ-TemplateErrorReporting' => 'foo@bar.com',
                'MJ-TemplateErrorDeliver' => 'deliver',
            ],
            'Attachments' => [
                [
                    'Content-type' => 'application/bar',
                    'Filename' => 'foo.bar',
                    'content' => base64_encode('baz')
                ],
            ],
        ]])->shouldBeCalled();
    }

    function it_should_send_mail_with_no_multipart_data(
        Swift_Mime_SimpleMessage $message,
        Client $client,
        Response $response,
        TemplateIdGuesserManager $manager,
        \Swift_Events_EventDispatcher $dispatcher,
        \Swift_Events_SendEvent $event,
        \Swift_Mime_SimpleHeaderSet $headers,
        \Swift_Attachment $attachment
    ) {
        $manager->guess($message)->willReturn('foo');
        $message->getFrom()->willReturn(['test@foo.com' => null]);
        $message->getTo()->willReturn(['to@foo.com' => null, 'bar@baz.com' => null]);
        $message->getSubject()->willReturn('Mail subject');
        $message->getBody()->willReturn('Mail body');
        $message->getHeaders()->willReturn($headers);
        $message->getChildren()->willReturn([$attachment]);

        $attachment->getFilename()->willReturn('foo.bar');
        $attachment->getContentType()->willReturn('multipart/alternative');
        $attachment->getBody()->willReturn('baz');

        $headers->getAll()->willReturn([]);

        $response->success()->willReturn(true);
        $client->post(Argument::any(), Argument::any())->willReturn($response);

        $dispatcher->createSendEvent(Argument::any(), Argument::any())->willReturn($event);
        $dispatcher->dispatchEvent($event, 'beforeSendPerformed')->shouldBeCalled();
        $event->bubbleCancelled()->willReturn(false);
        $event->setResult(\Swift_Events_SendEvent::RESULT_SUCCESS)->shouldBeCalled();
        $dispatcher->dispatchEvent($event, 'sendPerformed')->shouldBeCalled();

        $this->send($message)->shouldBeEqualTo(2);

        $client->post(Resources::$Email, ['body' => [
            'FromEmail' => 'test@foo.com',
            'Subject' => 'Mail subject',
            'Vars' => ['content' => 'Mail body'],
            'Recipients' => [
                ['Email' => 'to@foo.com', 'Name' => 'to@foo.com'],
                ['Email' => 'bar@baz.com', 'Name' => 'bar@baz.com'],
            ],
            'MJ-TemplateID' => 'foo',
            'MJ-TemplateLanguage' => 'True',
            'Headers' => [],
            'Attachments' => [
                [
                    'Filename' => 'foo.bar',
                    'content' => base64_encode('baz')
                ],
            ],
        ]])->shouldBeCalled();
    }

    function it_should_send_mail_and_dispatch_failed_message(
        Swift_Mime_SimpleMessage $message,
        Client $client,
        Response $response,
        TemplateIdGuesserManager $manager,
        \Swift_Events_EventDispatcher $dispatcher,
        \Swift_Events_SendEvent $event,
        \Swift_Mime_SimpleHeaderSet $headers
    ) {
        $manager->guess($message)->willReturn('foo');
        $message->getFrom()->willReturn(['test@foo.com' => null]);
        $message->getTo()->willReturn(['to@foo.com' => null, 'bar@baz.com' => null]);
        $message->getSubject()->willReturn('Mail subject');
        $message->getBody()->willReturn('Mail body');
        $message->getHeaders()->willReturn($headers);
        $message->getChildren()->willReturn([]);
        
        $headers->getAll()->willReturn([]);

        $response->success()->willReturn(false);
        $client->post(Argument::any(), Argument::any())->willReturn($response);

        $dispatcher->createSendEvent(Argument::any(), Argument::any())->willReturn($event);
        $dispatcher->dispatchEvent($event, 'beforeSendPerformed')->shouldBeCalled();
        $event->bubbleCancelled()->willReturn(false);
        $event->setResult(\Swift_Events_SendEvent::RESULT_FAILED)->shouldBeCalled();
        $dispatcher->dispatchEvent($event, 'sendPerformed')->shouldBeCalled();

        $this->send($message)->shouldBeEqualTo(0);

        $client->post(Resources::$Email, ['body' => [
            'FromEmail' => 'test@foo.com',
            'Subject' => 'Mail subject',
            'Vars' => ['content' => 'Mail body'],
            'Recipients' => [
                ['Email' => 'to@foo.com', 'Name' => 'to@foo.com'],
                ['Email' => 'bar@baz.com', 'Name' => 'bar@baz.com'],
            ],
            'MJ-TemplateID' => 'foo',
            'MJ-TemplateLanguage' => 'True',
            'Headers' => [
                'MJ-TemplateErrorReporting' => 'foo@bar.com',
                'MJ-TemplateErrorDeliver' => 'deliver',
            ],
        ]])->shouldBeCalled();
    }

    function it_should_fill_from_name(
        Swift_Mime_SimpleMessage $message,
        Client $client,
        Response $response,
        TemplateIdGuesserManager $manager,
        \Swift_Mime_SimpleHeaderSet $headers
    ) {
        $manager->guess($message)->willReturn('foo');
        $message->getFrom()->willReturn(['test@foo.com' => 'FooBar']);
        $message->getTo()->willReturn(['to@foo.com' => null, 'bar@baz.com' => null]);
        $message->getSubject()->willReturn('Mail subject');
        $message->getBody()->willReturn('Mail body');
        $message->getHeaders()->willReturn($headers);
        $message->getChildren()->willReturn([]);
        
        $headers->getAll()->willReturn([]);

        $response->success()->willReturn(false);
        $client->post(Argument::any(), Argument::any())->willReturn($response);

        $this->send($message)->shouldBeEqualTo(0);

        $client->post(Resources::$Email, ['body' => [
            'FromEmail' => 'test@foo.com',
            'FromName' => 'FooBar',
            'Subject' => 'Mail subject',
            'Vars' => ['content' => 'Mail body'],
            'Recipients' => [
                ['Email' => 'to@foo.com', 'Name' => 'to@foo.com'],
                ['Email' => 'bar@baz.com', 'Name' => 'bar@baz.com'],
            ],
            'MJ-TemplateID' => 'foo',
            'MJ-TemplateLanguage' => 'True',
            'Headers' => [
                'MJ-TemplateErrorReporting' => 'foo@bar.com',
                'MJ-TemplateErrorDeliver' => 'deliver',
            ],
        ]])->shouldBeCalled();
    }

    function it_should_not_send_mail_if_cancelled(
        Swift_Mime_SimpleMessage $message,
        Client $client,
        \Swift_Events_EventDispatcher $dispatcher,
        \Swift_Events_SendEvent $event
    ) {
        $dispatcher->createSendEvent(Argument::any(), Argument::any())->willReturn($event);
        $dispatcher->dispatchEvent($event, 'beforeSendPerformed')->shouldBeCalled();
        $event->bubbleCancelled()->willReturn(true);

        $this->send($message)->shouldBeEqualTo(0);

        $client->post(Argument::any())->shouldNotBeCalled();
    }

    function it_should_register_plugin(
        \Swift_Events_EventDispatcher $dispatcher,
        \Swift_Events_EventListener $plugin
    ) {
        $this->registerPlugin($plugin);

        $dispatcher->bindEventListener($plugin)->shouldBeCalled();
    }
}
