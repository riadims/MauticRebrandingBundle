<?php
// EventListener/RebrandingSubscriber.php
namespace MauticPlugin\MauticRebrandingBundle\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Mautic\CoreBundle\Event\TemplateEvent;

class RebrandingSubscriber implements EventSubscriberInterface
{
    protected $templating;
    
    public function __construct($templating)
    {
        $this->templating = $templating;
    }
    
    public static function getSubscribedEvents()
    {
        return [
            'mautic.template.header' => 'onHeaderRender',
        ];
    }
    
    public function onHeaderRender(TemplateEvent $event)
    {
        // Load saved settings (if available)
        $settingsFile = __DIR__ . '/../data/rebranding.json';
        $preferences  = [];
        if (file_exists($settingsFile)) {
            $preferences = json_decode(file_get_contents($settingsFile), true);
        }
        
        $vars = [
            'logoUrl'   => $preferences['logoUrl'] ?? '/plugins/MauticRebrandingBundle/Assets/default-logo.png',
            'siteTitle' => $preferences['siteTitle'] ?? 'Mautic',
            'mainColor' => $preferences['mainColor'] ?? '#0000ff'
        ];
        
        // Render your custom header template from the plugin folder
        $customHeader = $this->templating->render('MauticRebrandingBundle:Override:header.html.php', $vars);
        $event->setContent($customHeader);
    }
}
