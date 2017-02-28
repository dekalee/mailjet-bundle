<?php

namespace spec\Dekalee\MailjetBundle\Checker;

use Dekalee\MailjetBundle\Checker\ContactChecker;
use Mailjet\Response;
use PhpSpec\ObjectBehavior;
use Mailjet\Client;

class ContactCheckerSpec extends ObjectBehavior
{
    function let(Client $client)
    {
        $this->beConstructedWith($client);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(ContactChecker::CLASS);
    }

    function it_should_check_if_there_are_no_blocked_email(
        Client $client,
        Response $response
    ) {
        $response->success()->willReturn(true);
        $response->getBody()->willReturn([
            'Data' => [
                0 => [
                    'BlockedCount' => 0
                ]
            ]
        ]);
        $client->get(['contactstatistics', 'foo@test.co'])
            ->willReturn($response)
        ;

        $this->hasNoBlockedEmail('foo@test.co')->shouldBeEqualTo(true);
    }

    function it_should_check_if_there_are_blocked_email(
        Client $client,
        Response $response
    ) {
        $response->success()->willReturn(true);
        $response->getBody()->willReturn([
            'Data' => [
                0 => [
                    'BlockedCount' => 5
                ]
            ]
        ]);
        $client->get(['contactstatistics', 'foo@test.co'])
            ->willReturn($response)
        ;

        $this->hasNoBlockedEmail('foo@test.co')->shouldBeEqualTo(false);
    }

    function it_should_return_error_if_success_false(
        Client $client,
        Response $response
    ) {
        $response->success()->willReturn(false);
        $client->get(['contactstatistics', 'foo@test.co'])
            ->willReturn($response)
        ;

        $this->hasNoBlockedEmail('foo@test.co')->shouldBeEqualTo('error');
    }
}
