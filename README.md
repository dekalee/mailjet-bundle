#Mailjet Bundle

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/dekalee/mailjet-bundle/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/dekalee/mailjet-bundle/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/dekalee/mailjet-bundle/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/dekalee/mailjet-bundle/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/dekalee/mailjet-bundle/badges/build.png?b=master)](https://scrutinizer-ci.com/g/dekalee/mailjet-bundle/build-status/master)
[![Latest Stable Version](https://poser.pugx.org/dekalee/mailjet-bundle/v/stable)](https://packagist.org/packages/dekalee/mailjet-bundle)
[![Total Downloads](https://poser.pugx.org/dekalee/mailjet-bundle/downloads)](https://packagist.org/packages/dekalee/mailjet-bundle)
[![License](https://poser.pugx.org/dekalee/mailjet-bundle/license)](https://packagist.org/packages/dekalee/mailjet-bundle)

## Usage

This bundle provides a transport element to use `SwiftMailer` from the
`Symfony` project to send mail using `Mailjet`.

## Configuration

To configure the bundle, you will have to enter the api key, secret and default
template id :

``` yaml

    # app/config/config.yml
    dekalee_mailjet:
        api_key: %api_key%
        secret_key: %secret_key%
        base_template_id: %base_template_id%
```

You will also have to modify the transport in the mailer configuration :

``` yaml
    # app/config/config.yml
    swiftmailer:
        transport:  mailjet
```

## Extension

In mailjet, you can define your own template for the mail you send. It is
possible to use different template for each mail you will send.

To perform this action, you will need to guess the template Id from the
message being send.

### Create a guesser

You should implement the class : `Dekalee\MailjetBundle\Guesser\TemplateIdGuesserInterface`

There are two methods inside :

 - supports, which will decide if your strategy is able to give the
  template Id for this message
 - guess, which will return the template Id for the given message

### Declare the service

Once your class is created, you should add the tag `dekalee_mailjet.guesser.template_id.strategy`
to your service definition

## Campaign management

### Affiliate a user to a campaign

To affiliate a contact to a campaign, you should use the `ContactListSubscriber` class,
declared under the `dekalee_mailet.subscriber.contact_list` key in the container:

``` php
    class RegisterUser
    {
        protected $subscriber;

        public function __construct(ContactListSubscriber $subscriber)
        {
            $this->subscriber = $subscriber;
        }

        public function addUser(User $user)
        {
            $this->subscriber->subscribe(
                'campaignName',
                $user->getEmail(),
                [
                    'subject' => 'Mail subject (linked to the user)',
                    'content' => 'Mail content (linked to the user)',
                ]
            );
        }
    }
```

The template used for sending personnal emails to the contact from this list would be
able to use the variable `content_CampaignName` and `subject_CampaignName`.

### Unsubscribe a user from a campaign

To affiliate a contact to a campaign, you should use the `ContactListUnSubscriber` class,
declared under the `dekalee_mailet.unsubscriber.contact_list` key in the container:

``` php
    class UnRegisterUser
    {
        protected $unsubscriber;

        public function __construct(ContactListUnsubscriber $unsubscriber)
        {
            $this->unsubscriber = $unsubscriber;
        }

        public function delUser(User $user)
        {
            $this->unsubscriber->unsubscribe('campaignName', $user->getEmail());
        }
    }
```

## Limitation

For the moment, the bundle does not supports the inline attachement functionnality from mailjet,
but contributions are welcome :).
