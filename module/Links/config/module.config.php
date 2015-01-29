<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Links\Controller\Links' => 'Links\Controller\LinksController',
            'Links\Controller\Comments' => 'Links\Controller\CommentsController',
            'Links\Controller\Votes' => 'Links\Controller\VotesController'
        ),
    ),
    'router' => array(
        'routes' => array(
            'links' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/links[/:id]',
                    'constraints' => array(
                        'id'     => '[0-9A-Za-z\-]+'
                    ),
                    'defaults' => array(
                        'controller' => 'Links\Controller\Links',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'votes' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/votes[/:user]',
                            'constraints' => array(
                                'user'     => '[0-9A-Za-z\-]+'
                            ),
                            'defaults' => array(
                                'controller' => 'Links\Controller\Votes',
                            ),
                        ),
                        'may_terminate' => true
                    ),
                    'comments' => array(
                        'type'    => 'segment',
                        'options' => array(
                            'route'    => '/comments[/:comment]',
                            'constraints' => array(
                                'comment'     => '[0-9A-Za-z&\-]+',
                            ),
                            'defaults' => array(
                                'controller' => 'Links\Controller\Comments',
                            ),
                        ),
                    ),

                ),
            ),

        ),
    ),
    'view_manager' => array(
        'strategies' => array(
            'ViewJsonStrategy',
        ),
    ),
);