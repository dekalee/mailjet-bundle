<?php

namespace spec\Dekalee\MailjetBundle\Guesser;

use Dekalee\MailjetBundle\Guesser\TemplateIdGuesserManager;
use Dekalee\MailjetBundle\Guesser\TemplateIdGuesserInterface;
use PhpSpec\ObjectBehavior;
use Swift_Mime_Message;

class TemplateIdGuesserManagerSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('bar');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(TemplateIdGuesserManager::CLASS);
    }

    function it_should_return_the_base_template_id(Swift_Mime_Message $message)
    {
        $this->guess($message)->shouldBeEqualTo('bar');
    }

    function it_should_return_other_guesser_template_id_if_support(
        Swift_Mime_Message $message,
        TemplateIdGuesserInterface $guesser
    ) {
        $guesser->guess($message)->willReturn('foo');
        $guesser->supports($message)->willReturn(true);

        $this->addGuesser($guesser);
        $this->guess($message)->shouldBeEqualTo('foo');
    }

    function it_should_return_base_template_id_if_guesser_not_support(
        Swift_Mime_Message $message,
        TemplateIdGuesserInterface $guesser
    ) {
        $guesser->supports($message)->willReturn(false);
        $guesser->guess($message)->willReturn('foo');

        $this->addGuesser($guesser);
        $this->guess($message)->shouldBeEqualTo('bar');
    }
}
