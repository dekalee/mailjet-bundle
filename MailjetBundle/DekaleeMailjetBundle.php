<?php

namespace Dekalee\MailjetBundle;

use Dekalee\MailjetBundle\DependencyInjection\Compiler\TemplateIdGuesserCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class DekaleeMailjetBundle
 */
class DekaleeMailjetBundle extends Bundle
{
    /**
     * Builds the bundle.
     *
     * It is only ever called once when the cache is empty.
     *
     * This method can be overridden to register compilation passes,
     * other extensions, ...
     *
     * @param ContainerBuilder $container A ContainerBuilder instance
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new TemplateIdGuesserCompilerPass());
    }
}
