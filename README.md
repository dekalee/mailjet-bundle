#Mailjet Bundle

| Service | Badge |
| -------- |:--------:|
| Total Downloads | [![Total Downloads](https://poser.pugx.org/dekalee/mailjet-bundle/downloads)](https://packagist.org/packages/dekalee/mailjet-bundle) |
| Code quality (scrutinizer) | [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/dekalee/mailjet-bundle/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/dekalee/mailjet-bundle/?branch=master) |
| Code coverage (scrutinizer) | [![Code Coverage](https://scrutinizer-ci.com/g/dekalee/mailjet-bundle/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/dekalee/mailjet-bundle/?branch=master) |
| Build (scrutinizer) | [![Build Status](https://scrutinizer-ci.com/g/dekalee/mailjet-bundle/badges/build.png?b=master)](https://scrutinizer-ci.com/g/dekalee/mailjet-bundle/build-status/master) |

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
