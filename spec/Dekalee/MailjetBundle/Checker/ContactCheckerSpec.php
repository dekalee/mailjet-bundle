<?php

namespace spec\Dekalee\MailjetBundle\Checker;

use Dekalee\MailjetBundle\Checker\ContactChecker;
use Mailjet\Client;
use Mailjet\Resources;
use PhpSpec\ObjectBehavior;

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

    function it_should_check_if_there_are_no_blocked_email(Client $client)
    {
        $return = $this->hasNoBlockedEmail('foo@test.com')->shouldBeCalled();
        $return->shouldBeEqualTo(0);
    }
}
