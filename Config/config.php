<?php

return [
    'name'        => 'Rebranding',
    'description' => 'A plugin to customize Mautic branding.',
    'author'      => 'riadims',
    'version'     => '1.0.0',
    
    'routes' => [
        'main' => [
            'rebranding' => [
                'path'       => '/rebranding',
                'controller' => 'MauticPlugin\RebrandingBundle\Controller\SettingsController::viewAction',
            ],
            'rebranding_save' => [
                'path'       => '/rebranding/save',
                'controller' => 'MauticPlugin\RebrandingBundle\Controller\SettingsController::saveAction',
                'methods'    => ['POST'],
            ],
            'rebranding_revert' => [
            'path'       => '/rebranding/revert',
            'controller' => 'MauticPlugin\RebrandingBundle\Controller\SettingsController::revertAction',
            'methods'    => ['POST'],
            ],
        ],
    ],

    'menu' => [
        'main' => [
            'rebranding' => [
                'id'        => 'rebranding_menu',
                'iconClass' => 'fa-paint-brush',
                'route'     => 'rebranding',
                'label'     => 'Rebranding Settings',
                'priority'  => 0,
                'linkAttributes' => [
                    'data-toggle' => 'ajax',
                    'href'        => '/s/rebranding',
                ],
            ],
        ],
    ],

    'services' => [
    'events' => [
        'rebranding.assets.subscriber' => [
            'class'     => \MauticPlugin\RebrandingBundle\EventListener\AssetsSubscriber::class,
            'arguments' => [],
            'tags'      => ['kernel.event_subscriber'],
            ],
        ],
    ],

];
