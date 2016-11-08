<?php
/**
 * Routes configuration plugin Admin
 */




Router::connect('/admin', array( 'controller' => 'admin_pages', 'plugin' => 'admin','action' => 'dashboard'));


/* Home - Slider */
Router::connect('/admin/section_first', array( 'controller' => 'admin_slides', 'plugin' => 'admin', 'action' => 'index'));
Router::connect('/admin/section_first/add', array( 'controller' => 'admin_slides', 'plugin' => 'admin', 'action' => 'add'));
Router::connect(
    '/admin/section_first/edit/:id',
    array( 'controller' => 'admin_slides', 'plugin' => 'admin', 'action' => 'edit'),
    [ 'id' => ['id'],
       'id' => '[0-9]+']
);

Router::connect(
    '/admin/section_first/delete/:id',
    array( 'controller' => 'admin_slides', 'plugin' => 'admin', 'action' => 'del'),
    [ 'id' => ['id'],
       'id' => '[0-9]+']
);

/* Home - rental_fees*/
Router::connect('/admin/rental_fees', array( 'controller' => 'adminRentalsFees', 'plugin' => 'admin', 'action' => 'index'));
Router::connect('/admin/rental_fees/add', array( 'controller' => 'adminRentalsFees', 'plugin' => 'admin', 'action' => 'add'));
Router::connect(
    '/admin/rental_fees/edit/:id',
    array( 'controller' => 'adminRentalsFees', 'plugin' => 'admin', 'action' => 'edit'),
    [
        'id' => ['id'],
        'id' => '[0-9]+']
);



Router::connect('/admin/pages', array( 'controller' => 'admin_pages', 'plugin' => 'admin', 'action' => 'index'));
Router::connect('/admin/pages/:action/*', array( 'controller' => 'admin_pages', 'plugin' => 'admin'));

Router::connect('/admin/publications', array( 'controller' => 'admin_publications', 'plugin' => 'admin', 'action' => 'index'));
Router::connect('/admin/publications/:action/*', array( 'controller' => 'admin_publications', 'plugin' => 'admin'));

//Router::connect('/admin/statics/*', array( 'controller' => 'admin_statics', 'plugin' => 'admin', 'action' => 'edit'));

Router::connect('/admin/storytabs', array( 'controller' => 'admin_storytabs', 'plugin' => 'admin', 'action' => 'index'));
Router::connect('/admin/storytabs/:action/*', array( 'controller' => 'admin_storytabs', 'plugin' => 'admin'));

Router::connect('/admin/discounts', array( 'controller' => 'admin_discounts', 'plugin' => 'admin', 'action' => 'index'));
Router::connect('/admin/discounts/:action/*', array( 'controller' => 'admin_discounts', 'plugin' => 'admin'));

Router::connect('/admin/news', array( 'controller' => 'admin_tidings', 'plugin' => 'admin', 'action' => 'index'));
Router::connect('/admin/news/:action/*', array( 'controller' => 'admin_tidings', 'plugin' => 'admin'));
Router::connect('/admin/tidcategory', array( 'controller' => 'admin_tid_categories', 'plugin' => 'admin', 'action' => 'index'));
Router::connect('/admin/tidcategory/:action/*', array( 'controller' => 'admin_tid_categories', 'plugin' => 'admin'));


Router::connect('/admin/tags', array( 'controller' => 'admin_tags', 'plugin' => 'admin', 'action' => 'index'));
Router::connect('/admin/tags/:action/*', array( 'controller' => 'admin_tags', 'plugin' => 'admin'));

//Router::connect(
//    '/admin/tidcategories/edit/:id',
//    array( 'controller' => 'admin_tid_categories', 'plugin' => 'admin', 'action' => 'edit'),
//    [ 'id' => ['id'],
//        'id' => '[0-9]+']
//);
Router::connect('/admin/comments', array( 'controller' => 'admin_tidings', 'plugin' => 'admin', 'action' => 'index'));
Router::connect('/admin/news/:action/*', array( 'controller' => 'admin_tidings', 'plugin' => 'admin'));


Router::connect('/admin/categories', array( 'controller' => 'admin_categories', 'plugin' => 'admin', 'action' => 'index'));
Router::connect('/admin/categories/:action/*', array( 'controller' => 'admin_categories', 'plugin' => 'admin'));



/* Products */
Router::connect('/admin/products', [ 'controller' => 'adminProducts', 'plugin' => 'admin', 'action' => 'index']);
Router::connect('/admin/products/add', ['controller' => 'adminProducts', 'plugin' => 'admin', 'action' => 'add']);
Router::connect(
    '/admin/products/edit/:id',
    array( 'controller' => 'admin_products', 'plugin' => 'admin', 'action' => 'edit'),
    array(
        'id' => array('id'),
        'id' => '[0-9]+'
    )
);
Router::connect(
    '/admin/products/delete/:id',
    array( 'controller' => 'admin_products', 'plugin' => 'admin', 'action' => 'del'),
    array(
        'id' => array('id'),
        'id' => '[0-9]+'
    )
);

Router::connect('/admin/products/:action/*', array( 'controller' => 'adminProducts', 'plugin' => 'admin'));


/* Accessoiries*/
Router::connect('/admin/accessories', [ 'controller' => 'adminAccessories', 'plugin' => 'admin', 'action' => 'index']);
Router::connect('/admin/accessories/add', ['controller' => 'adminAccessories', 'plugin' => 'admin', 'action' => 'add']);
Router::connect(
    '/admin/accessories/edit/:id',
    array( 'controller' => 'adminAccessories', 'plugin' => 'admin', 'action' => 'edit'),
    array(
        'id' => array('id'),
        'id' => '[0-9]+'
    )
);
Router::connect(
    '/admin/accessories/delete/:id',
    array( 'controller' => 'adminAccessories', 'plugin' => 'admin', 'action' => 'del'),
    array(
        'id' => array('id'),
        'id' => '[0-9]+'
    )
);

//Router::connect('/admin/products/:action/*', array( 'controller' => 'admin_products', 'plugin' => 'admin'));

/* Home Parts*/
Router::connect('/admin/home_parts', array( 'controller' => 'adminHomeParts', 'plugin' => 'admin', 'action' => 'index'));
Router::connect(
    '/admin/home_parts/edit/:id',
    array( 'controller' => 'adminHomeParts', 'plugin' => 'admin', 'action' => 'edit'),
    array(
        'id' => array('id'),
        'id' => '[0-9]+'
    )
);

/* FAQ*/
Router::connect('/admin/faq', array( 'controller' => 'adminFaq', 'plugin' => 'admin', 'action' => 'index'));
Router::connect('/admin/faq/add', ['controller' => 'adminFaq', 'plugin' => 'admin', 'action' => 'add']);
Router::connect(
    '/admin/faq/edit/:id',
    array( 'controller' => 'adminFaq', 'plugin' => 'admin', 'action' => 'edit'),
    array(
        'id' => array('id'),
        'id' => '[0-9]+'
    )
);
Router::connect(
    '/admin/faq/delete/:id',
    array( 'controller' => 'adminFaq', 'plugin' => 'admin', 'action' => 'del'),
    [ 'id' => ['id'],
       'id' => '[0-9]+'
        ]
);

/* gallery */
Router::connect('/admin/gallery', array( 'controller' => 'adminGallery', 'plugin' => 'admin', 'action' => 'index'));
Router::connect('/admin/gallery/add', ['controller' => 'adminGallery', 'plugin' => 'admin', 'action' => 'add']);
Router::connect(
    '/admin/gallery/edit/:id',
    array( 'controller' => 'adminGallery', 'plugin' => 'admin', 'action' => 'edit'),
    array(
        'id' => array('id'),
        'id' => '[0-9]+'
    )
);
Router::connect(
    '/admin/gallery/delete/:id',
    array( 'controller' => 'adminGallery', 'plugin' => 'admin', 'action' => 'del'),
    [ 'id' => ['id'],
      'id' => '[0-9]+'
    ]
);


/* About us*/
Router::connect('/admin/about', array( 'controller' => 'adminAbout', 'plugin' => 'admin', 'action' => 'index'));
Router::connect(
    '/admin/about/edit/:id',
    array( 'controller' => 'adminAbout', 'plugin' => 'admin', 'action' => 'edit'),
    array(
        'id' => array('id'),
        'id' => '[0-9]+'
    )
);


/*how it work*/
Router::connect('/admin/how_it_works', array( 'controller' => 'adminHowItWorks', 'plugin' => 'admin', 'action' => 'index'));
Router::connect(
    '/admin/how_it_works/edit/:id',
    array( 'controller' => 'adminHowItWorks', 'plugin' => 'admin', 'action' => 'edit'),
    array(
        'id' => array('id'),
        'id' => '[0-9]+'
    )
);






//Router::connect('/admin/menus', array( 'controller' => 'admin_menus', 'plugin' => 'admin', 'action' => 'index'));
Router::connect('/admin/footer', array( 'controller' => 'admin_menus', 'plugin' => 'admin', 'action' => 'index'));
Router::connect('/admin/footer/:action/*', array( 'controller' => 'admin_menus', 'plugin' => 'admin'));

Router::connect('/admin/footer_right', array( 'controller' => 'admin_menus', 'plugin' => 'admin', 'action' => 'footerRight'));
Router::connect('/admin/footer_right/:action/*', array( 'controller' => 'admin_menus', 'plugin' => 'admin'));





Router::connect('/admin/testimonials', array( 'controller' => 'admin_testimonials', 'plugin' => 'admin', 'action' => 'index'));
Router::connect('/admin/testimonials/:action/*', array( 'controller' => 'admin_testimonials', 'plugin' => 'admin'));


Router::connect('/admin/companies', array( 'controller' => 'admin_companies', 'plugin' => 'admin', 'action' => 'index'));
Router::connect('/admin/companies/:action/*', array( 'controller' => 'admin_companies', 'plugin' => 'admin'));

Router::connect('/admin/users', array( 'controller' => 'admin_users', 'plugin' => 'admin', 'action' => 'index'));
Router::connect('/admin/users/:action/*', array( 'controller' => 'admin_users', 'plugin' => 'admin'));

Router::connect('/admin/services', array( 'controller' => 'admin_services', 'plugin' => 'admin', 'action' => 'index'));
Router::connect('/admin/service s/:action/*', array( 'controller' => 'admin_services', 'plugin' => 'admin'));

Router::connect('/admin/orders', array( 'controller' => 'admin_orders', 'plugin' => 'admin', 'action' => 'index'));
Router::connect('/admin/orders/new', array( 'controller' => 'admin_orders', 'plugin' => 'admin', 'action' => 'newOrders'));
Router::connect('/admin/orders/:action/*', array( 'controller' => 'admin_orders', 'plugin' => 'admin'));

Router::connect('/admin/options', array( 'controller' => 'admin_options', 'plugin' => 'admin', 'action' => 'index'));
Router::connect('/admin/options/:action/*', array( 'controller' => 'admin_options', 'plugin' => 'admin'));
Router::connect('/admin/assignments', array( 'controller' => 'admin_contacts', 'plugin' => 'admin', 'action' => 'assignments'));
Router::connect('/admin/assignments/view/*', array( 'controller' => 'admin_contacts', 'plugin' => 'admin', 'action' => 'assignmentView'));
Router::connect('/admin/assignments/delete/*', array( 'controller' => 'admin_contacts', 'plugin' => 'admin', 'action' => 'assignmentDelete'));

Router::connect('/admin/claims', array( 'controller' => 'admin_claims', 'plugin' => 'admin', 'action' => 'index'));
Router::connect('/admin/claims/:action/*', array('controller' => 'admin_claims', 'plugin' => 'admin'));

Router::connect('/admin/contacts', array( 'controller' => 'admin_contacts', 'plugin' => 'admin', 'action' => 'index'));
Router::connect('/admin/contacts/:action/*', array( 'controller' => 'admin_contacts', 'plugin' => 'admin'));

Router::connect('/admin/subscribe', array( 'controller' => 'admin_subscribes', 'plugin' => 'admin', 'action' => 'index'));
Router::connect('/admin/subscribe/:action/*', array( 'controller' => 'admin_subscribes', 'plugin' => 'admin'));

Router::connect('/admin/feedbacks', array( 'controller' => 'admin_feedbacks', 'plugin' => 'admin', 'action' => 'index'));
Router::connect('/admin/feedbacks/:action/*', array( 'controller' => 'admin_feedbacks', 'plugin' => 'admin'));



/*Ajax*/
Router::connect('/admin/ajax/products', array( 'controller' => 'admin_product_rentals', 'plugin' => 'admin', 'action' => 'ajax'));
Router::connect('/admin/ajax/rent_fee', array( 'controller' => 'adminRentalsFees', 'plugin' => 'admin', 'action' => 'ajax'));
