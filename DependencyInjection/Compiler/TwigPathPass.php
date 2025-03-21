<?php

namespace MauticPlugin\RebrandingBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class TwigPathPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $loaderServices = ['twig.loader.native_filesystem', 'twig.loader'];

        $projectDir = $container->getParameter('kernel.project_dir');
        $corePath = $projectDir . '/app/bundles/CoreBundle/Resources/views';

        foreach ($loaderServices as $serviceName) {
            if ($container->hasDefinition($serviceName)) {
                $definition = $container->getDefinition($serviceName);
                $definition->addMethodCall('addPath', [__DIR__ . '/../../Resources/views', 'RebrandingBundle']);
                $definition->addMethodCall('addPath', [$corePath, 'MauticCoreBundle']);
            }
        }
    }
}
