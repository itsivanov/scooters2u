    <?php
App::uses('AppModel', 'Model');
App::uses('CakeSession', 'Model/Datasource');

/**
 * OrderItem Model
 *
 */
class OrderItem extends AppModel {

    public $belongsTo = [
        'Order',
        'Product'
    ];
}
