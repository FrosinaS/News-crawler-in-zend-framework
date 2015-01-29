<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'WebSite\Controller\Index' => 'WebSite\Controller\IndexController',
            'WebSite\Controller\Admin' => 'WebSite\Controller\AdminController',
        ),
    ),

    // The following section is new and should be added to your file
    'router' => array(
        'routes' => array(
            'news' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/news[/][:id][/:action]',
                    'constraints' => array(
                        'action' => '[a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'WebSite\Controller\Index',
                        'action'     => 'index',
                    ),
                ),
            ),
            'adminpanel' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/adminpanel[/][:action]',
                    'constraints' => array(
                        'action' => '[a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'WebSite\Controller\Admin',
                        'action'     => 'adminPanel',
                    ),
                ),
            ),
        ),
    ),

    'view_manager' => array(
        'template_map' =>array(
            'index/link-template' => __DIR__ . '/../view/web-site/index/link-template.phtml',
            'index/comments-template' => __DIR__ . '/../view/web-site/index/comments-template.phtml',
        ),
        'template_path_stack' => array(
            'web-site' => __DIR__ . '/../view',
        ),

    ),
);