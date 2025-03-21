<?php
namespace MauticPlugin\RebrandingBundle\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Mautic\CoreBundle\CoreEvents;
use Mautic\CoreBundle\Event\CustomAssetsEvent;

class AssetsSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            CoreEvents::VIEW_INJECT_CUSTOM_ASSETS => ['injectAssets', 0],
        ];
    }

    public function injectAssets(CustomAssetsEvent $event)
    {
        $event->addScript('/plugins/RebrandingBundle/Assets/js/rebranding.js');
        $event->addStylesheet('/plugins/RebrandingBundle/Assets/css/rebranding.css');
    }
}