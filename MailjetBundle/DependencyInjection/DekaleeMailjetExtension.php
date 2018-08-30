<?php

namespace Dekalee\MailjetBundle\DependencyInjection;

use Dekalee\MailjetBundle\Guesser\Strategy\SimpleTemplateGuesser;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\Kernel;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class DekaleeMailjetExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);
        
        $container->setParameter('dekalee_mailjet.key.api', $config['api_key']);
        $container->setParameter('dekalee_mailjet.key.secret', $config['secret_key']);
        $container->setParameter('dekalee_mailjet.template.fallback', $config['base_template_id']);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('client.yml');
        $loader->load('transport.yml');
        $loader->load('guesser.yml');
        $loader->load('creator.yml');
        $loader->load('data_collector.yml');

        if (4 === Kernel::MAJOR_VERSION || array_key_exists('DoctrineBundle', $container->getParameter('kernel.bundles'))) {
            $loader->load('convertor.yml');
            $loader->load('repository.yml');
            $loader->load('manager.yml');
        }

        foreach ($config['simple_template_choice'] as $class => $templateId) {
            $container
                ->register('dekalee.mailjet.simple_choice.' . $class, SimpleTemplateGuesser::CLASS)
                ->setArguments([
                    $class,
                    $templateId
                ])
                ->addTag('dekalee_mailjet.guesser.template_id.strategy');
        }
    }
}
