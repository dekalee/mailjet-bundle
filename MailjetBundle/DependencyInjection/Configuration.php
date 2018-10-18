<?php

namespace Dekalee\MailjetBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('dekalee_mailjet');

        $rootNode->children()
            ->scalarNode('api_key')->isRequired()->info('Mailjet API key')->end()
            ->scalarNode('secret_key')->isRequired()->info('Mailjet API token')->end()
            ->scalarNode('base_template_id')->isRequired()->info('Mailjet fallback template')->end()
            ->arrayNode('simple_template_choice')
                ->useAttributeAsKey('message_class')
                ->prototype('integer')
                ->end()
            ->end()
            ->scalarNode('reporting_email')->defaultNull()->info('Email where the error reporting should be send')->end()
            ->booleanNode('force_deliver')->defaultFalse()->info('If the email should be force delivered if there is a syntax error')
        ->end();

        return $treeBuilder;
    }
}
