<?php
return array(
    'name'        => 'MauticRebrandingBundle',
    'description' => 'Allows for site rebranding',
    'version'     => '1.0.0',
    'author'      => 'riadims',
    'routes'      => [
        'main' => array(
            'mautic_rebranding_index' => [
                'path'       => '/rebranding',
                'controller' => 'MauticRebrandingBundle:Rebranding:indexAction',
            ],
        ),
    ],
    'services' => [
        'events' => [
            'mautic.rebranding.subscriber' => [
                'class'     => \MauticPlugin\MauticRebrandingBundle\EventListener\RebrandingSubscriber::class,
                'arguments' => ['@templating'],
            ],
        ],
    ],
);
