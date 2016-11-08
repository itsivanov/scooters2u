<?php
/**
 * Routes configuration plugin Thumbs
 */
    Router::connect('/thumbs/*', array('plugin' => 'thumbs', 'controller' => 'thumbs', 'action' => 'index'));

/**
 * Load the CakePHP default routes. Remove this if you do not want to use
 * the built-in default routes.
 */
 //   require CAKE . 'Config' . DS . 'routes.php';