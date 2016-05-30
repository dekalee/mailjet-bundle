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
        ->end();

        return $treeBuilder;
    }
}
