<?php
namespace BasicInvoices\Tax;

use Zend\Router\Http\Segment;
use Zend\ServiceManager\Factory\InvokableFactory;

return [
    'router' => [
        'routes' => [
            'taxes' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/settings/taxes[/:action]',
                    'defaults' => [
                        'controller' => Controller\TaxesController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\TaxesController::class => Service\TaxesControllerFactory::class,
        ],
    ],
    'service_manager' => [
        'factories' => [
            'TaxManager' => Service\TaxManagerServiceFactory::class,
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
];
