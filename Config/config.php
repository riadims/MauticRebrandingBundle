<?php

return [
    'name'        => 'Rebranding',
    'description' => 'A plugin to customize Mautic branding.',
    'author'      => 'riadims',
    'version'     => '1.0.0',
    'icon'        => 'plugins/RebrandingBundle/Resources/images/icon.webp',

    'routes' => [
        'main' => [
            'rebranding' => [
                'path'       => '/s/rebranding',
                'controller' => 'MauticPlugin\RebrandingBundle\Controller\SettingsController::viewAction',
            ],
            'rebranding_save' => [
                'path'       => '/s/rebranding/save',
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
        'admin' => [
            'priority' => 100,
            'items'    => [
                'Rebranding' => [
                    'id'        => 'rebranding_settings',
                    'route'     => 'rebranding',
                    'access'    => 'admin',
                    'iconClass' => 'fa fa-paint-brush',
                    'children'  => [],
                    'priority'  => 0,
                ]
            ]
        ]
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