<?php

namespace MauticPlugin\RebrandingBundle;

use Mautic\PluginBundle\Bundle\PluginBundleBase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use MauticPlugin\RebrandingBundle\DependencyInjection\Compiler\TwigPathPass;

class RebrandingBundle extends PluginBundleBase
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->addCompilerPass(new TwigPathPass());
    }

    public function onPluginLoad()
    {
        $twig = $this->container->get('twig');
        $projectDir = $this->container->getParameter('kernel.project_dir');
        $coreTemplate = $projectDir . '/app/bundles/CoreBundle/Resources/views/Default/content.html.twig';
        $twig->addGlobal('mauticCoreContent', $coreTemplate);
    }
}
