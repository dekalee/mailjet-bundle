<?php

namespace Dekalee\MailjetBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class TemplateIdGuesserCompilerPass
 */
class TemplateIdGuesserCompilerPass implements CompilerPassInterface
{
    /**
     * You can modify the container here before it is dumped to PHP code.
     *
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        $managerName = 'dekalee_mailjet.guesser.template_id.manager';
        $tagName = 'dekalee_mailjet.guesser.template_id.strategy';
        $methodName = 'addGuesser';

        if (!$container->hasDefinition($managerName)) {
            return;
        }
        $manager = $container->getDefinition($managerName);
        $strategies = $container->findTaggedServiceIds($tagName);
        foreach ($strategies as $id => $attributes) {
            $manager->addMethodCall($methodName, array(new Reference($id)));
        }
    }
}
