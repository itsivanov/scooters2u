<?php
/**
 * Here, we are connecting '/' (base path) to controller called 'Pages',
 * its action called 'display', and we pass a param to select the view file
 * to use (in this case, /app/View/Pages/home.ctp)...
 */
    Router::parseExtensions('html', 'php', 'rss', 'pdf');
    Router::connect('/thumbs/*', array('plugin' => 'thumbs', 'controller' => 'thumbs', 'action' => 'index'));

	Router::connect('/', array('controller' => 'pages', 'action' => 'home', 'pageKey' => 'home'));

    /**
     * Scooters
     * Pages
     */
    Router::connect('/support', array('controller' => 'pages', 'action' => 'support', 'pageKey' => 'support'));
    Router::connect('/how-it-works', array('controller' => 'pages', 'action' => 'howItWorks', 'pageKey' => 'how_it_works'));
    Router::connect('/faq', array('controller' => 'pages', 'action' => 'faq', 'pageKey' => 'faq '));
    Router::connect('/get-started', array('controller' => 'pages', 'action' => 'getStarted', 'pageKey' => 'get_started'));
    Router::connect(
        '/get-started/:id',
        array( 'controller' => 'products', 'action' => 'details', 'pageKey' => 'get_started'),
        array(
            'id' => array('id'),
            'id' => '[0-9]+'
        )
    );
    Router::connect('/get-started/order', array('controller' => 'products', 'action' => 'orderProduct', 'pageKey' => 'get_started'));
    Router::connect('/get-started/order/payment', array('controller' => 'products', 'action' => 'payment', 'pageKey' => 'get_started'));



    Router::connect('/about-us', array('controller' => 'pages', 'action' => 'about', 'pageKey' => 'about_us'));
    Router::connect('/contact-us', array('controller' => 'pages', 'action' => 'contact', 'pageKey' => 'contact-us'));

    /*News*/
    Router::connect('/news', array('controller' => 'tidings', 'action' => 'index', 'pageKey' => 'news'));
    Router::connect('/news/:slug', array('controller' => 'tidings', 'action' => 'view', 'pageKey' => 'news'));
    Router::connect('/news/*', array('controller' => 'tidings', 'action' => 'index', 'pageKey' => 'news'));
    Router::connect('/search', array('controller' => 'tidings', 'action' => 'search', 'pageKey' => 'news'));
    Router::connect('/category', array('controller' => 'tidings', 'action' => 'category', 'pageKey' => 'news'));
    Router::connect('/tag', array('controller' => 'tidings', 'action' => 'tag', 'pageKey' => 'news'));


    Router::connect('/product/**', array( 'controller' => 'products', 'action' => 'viewByUrl', 'pageKey' => 'products'));
    Router::connect('/products/view/*', array( 'controller' => 'products', 'action' => 'view', 'pageKey' => 'products'));
    Router::connect(
        '/products/re/:id',
        array( 'controller' => 'products', 'action' => 're', 'pageKey' => 'products'),
        array(
            'pass' => array('id'),
            'id' => '[0-9]+'
        )
        );
    Router::connect('/products/:action/*', array( 'controller' => 'products', 'pageKey' => 'products'));
    /* AJAX */
    Router::connect('/ajax/details/', array( 'controller' => 'products', 'action' => 'ajaxDetails'));
    Router::connect('/ajax/details_order/', array( 'controller' => 'products', 'action' => 'ajaxOrderDetails'));
    Router::connect('/ajax/details_order_2/', array( 'controller' => 'products', 'action' => 'ajaxOrderDetailsSecond'));
    Router::connect('/ajax/pay/', array( 'controller' => 'products', 'action' => 'ajax_payment'));
    Router::connect('/ajax/rentalFee/', array( 'controller' => 'products', 'action' => 'rentalFee'));

    /**
     * End of
     * Scooters
     */

    Router::connect('/users', array( 'controller' => 'users', 'pageKey' => 'users'));
    Router::connect('/users/:action/*', array( 'controller' => 'users', 'pageKey' => 'users'));

    Router::connect('/pages/:action/*', array( 'controller' => 'pages'));

    Router::connect('/min-js', array('plugin' => 'Minify', 'controller' => 'minify', 'action' => 'index', 'js'));
    Router::connect('/min-css', array('plugin' => 'Minify', 'controller' => 'minify', 'action' => 'index', 'css'));

    Router::connect('/captcha', array('controller' => 'pages', 'action' => 'captcha', 'pageKey' => 'captcha'));
    /**
     * Load all plugin routes. See the CakePlugin documentation on
     * how to customize the loading of plugin routes.
     */
	CakePlugin::routes();

    Router::connect('/:pageKey', array('controller' => 'pages', 'action' => 'display'));
//    Router::connect('/:pageKey/:param', array('controller' => 'pages', 'action' => 'display'));

/**
 * Load the CakePHP default routes. Only remove this if you do not want to use
 * the built-in default routes.
 */
	require CAKE . 'Config' . DS . 'routes.php';