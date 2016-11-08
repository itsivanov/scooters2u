<?php
App::uses('AppModel', 'Model');

/**
 * Product Model
 *
 */

class Accessory extends AppModel {

    public $uses = 'OrderAccessory';


    public function getAll()
    {
        return $this->find('all');
    }

    public function getOnId($id)
    {
        return $this->find('first', [
            'conditions' => [
                'id' => $id
            ]
        ]);
    }

    public function getOrderAccess($order_id)
    {
        $options['joins'] = [
            ['table' => 'order_accessories',
                'alias' => 'OrderAccessory',
                'type' => 'LEFT',
                'conditions' => [
                    'Accessory.id = OrderAccessory.accessory_id',
                    'OrderAccessory.order_id' => $order_id
                ],
            ]
        ];

        $options['fields'] =[
            'Accessory.*',
            'OrderAccessory.*'
        ];

        return $this->find('all', $options);

    }



}
