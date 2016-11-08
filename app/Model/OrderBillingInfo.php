<?php
App::uses('AppModel', 'Model');
App::uses('CakeSession', 'Model/Datasource');

/**
 * Order Model
 *
 */
class OrderBillingInfo extends AppModel {

    public $uses = 'Order';

    public function getAll()
    {
        $options['joins'] = [
            ['table' => 'orders',
                'alias' => 'Order',
                'type' => 'INNER',
                'conditions' => [
                    'Order.id = OrderBillingInfo.order_id'
                ],
                'order' => ['OrderBillingInfo.order_id DESC'],
//                'group' => ['OrderBillingInfo.order_id']
            ]
        ];

        $options['fields'] =[
            'OrderBillingInfo.*',
            'Order.*'
        ];

        return $this->find('all', $options);
    }

}
