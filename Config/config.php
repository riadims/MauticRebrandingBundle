<?php

return [
    'name'        => 'MauticRebrandingBundle',
    'description' => 'Allows for site rebranding',
    'version'     => '1.0',
    'author'      => 'Mautic Community',
    'routes'      => [
        'main' => [
            'mautic_rebranding_index' => [
                'path'       => '/rebranding',
                'controller' => 'MauticRebrandingBundle:Rebranding:index',
            ],
        ],
    ],
    'services' => [
        'events' => [
            'mautic.rebranding.subscriber' => [
                'class'     => \MauticPlugin\MauticRebrandingBundle\EventListener\RebrandingSubscriber::class,
                'arguments' => ['@templating'],
            ],
        ],
    ],
];