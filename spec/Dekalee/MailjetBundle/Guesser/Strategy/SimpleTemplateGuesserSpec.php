<?php

namespace spec\Dekalee\MailjetBundle\Guesser\Strategy;

use Dekalee\MailjetBundle\Guesser\Strategy\SimpleTemplateGuesser;
use Dekalee\MailjetBundle\Guesser\TemplateIdGuesserInterface;
use PhpSpec\ObjectBehavior;

class SimpleTemplateGuesserSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith(foo::CLASS, 1);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(SimpleTemplateGuesser::CLASS);
    }

    function it_should_be_a_template_guesser()
    {
        $this->shouldHaveType(TemplateIdGuesserInterface::CLASS);
    }

    function it_should_support_foo_class_only(\Swift_Mime_Message $message, foo $foo)
    {
        $this->supports($message)->shouldBeEqualTo(false);
        $this->supports($foo)->shouldBeEqualTo(true);
    }

    function it_should_return_template_id(\Swift_Mime_Message $message)
    {
        $this->guess($message)->shouldBeEqualTo(1);
    }
}

class foo extends \Swift_Message {
}
