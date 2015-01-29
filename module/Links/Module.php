<?php
namespace Links;

use Links\Model\Link;
use Links\Model\LinkTable;
use Links\Model\Comment;
use Links\Model\CommentTable;
use Links\Model\UserTable;
use Links\Model\User;
use Links\Model\Vote;
use Links\Model\VoteTable;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;

class Module
{
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'Links\Model\LinkTable' =>  function($sm) {
                    $tableGateway = $sm->get('LinkTableGateway');
                    $table = new LinkTable($tableGateway);
                    return $table;
                },
                'Links\Model\CommentTable' =>  function($sm) {
                    $tableGateway = $sm->get('CommentTableGateway');
                    $table = new CommentTable($tableGateway);
                    return $table;
                },
                'LinkTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Link());
                    return new TableGateway('link', $dbAdapter, null, $resultSetPrototype);
                },
                'CommentTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Comment());
                    return new TableGateway('comments', $dbAdapter, null, $resultSetPrototype);
                },
                'Links\Model\UserTable' =>  function($sm) {
                    $tableGateway = $sm->get('UserTableGateway');
                    $table = new UserTable($tableGateway);
                    return $table;
                },
                'UserTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new User());
                    return new TableGateway('user', $dbAdapter, null, $resultSetPrototype);
                },
                'Links\Model\VoteTable' =>  function($sm) {
                    $tableGateway = $sm->get('VoteTableGateway');
                    $table = new VoteTable($tableGateway);
                    return $table;
                },
                'VoteTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Vote());
                    return new TableGateway('user_votes', $dbAdapter, null, $resultSetPrototype);
                },


            ),
        );
    }
}