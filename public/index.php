<?php

/**
 * Front controller
 *
 * PHP version 7.1
 */

// echo 'Requested URL = "' . $_SERVER['QUERY_STRING'] . '"';

require "../Core/Router.php";

$router = new Router();

// echo get_class($router);
// Add the routes to routing table
$router->add('',['controller'=>'Home', 'action' => 'index']);
$router->add('posts', ['controller'=>'Posts', 'action'=>'index']);
$router->add('posts/new', ['controller'=>'Posts', 'action'=>'new']);

// Displaying the routing table
echo '<pre>';
var_dump($router->getRoutes());
echo '</pre>';