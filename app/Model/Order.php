<?php
App::uses('AppModel', 'Model');
App::uses('CakeSession', 'Model/Datasource');

/**
 * Order Model
 *
 */
class Order extends AppModel {

    public $belongsTo = [
        'OrderStatus',

    ];

    public $hasOne = [
        'OrderBillingInfo',
        'OrderAccessory'
//        'OrderShippingInfo'
    ];

    public $hasMany = [
//        'OrderBillingInfo'
    ];

    public $actsAs = [
        'Containable',
        'Captcha' => [
            'field' => ['captcha'],
            'error' => 'Incorrect captcha code value'
        ]
    ];

    public $validate = [
//        'user_id' => [
//            [
//                'rule' => 'notEmpty', 'message' => 'Company name cannot be left blank'
//            ]
//        ],
        'phone' => [
            [
                'rule' => 'notEmpty', 'message' => 'Company name cannot be left blank'
            ]
        ],
        'full_name' => [
            [
                'rule' => 'notEmpty', 'message' => 'Company name cannot be left blank'
            ]
        ],
        'address' => [
            [
                'rule' => 'notEmpty', 'message' => 'Company name cannot be left blank'
            ]
        ]
    ];



    public function countNewOrders()
    {
        $settings = [
            'conditions' => [
                'Order.orderstatus_id' => 1
            ]
        ];

        return $this->find('count', $settings);
    }

    public function getStatusesList()
    {
        return $this->OrderStatus->find('list', ['fields' => ['id', 'status']]);
    }

    public function getStatusesListCMS()
    {
        return $this->OrderStatus->find('list', ['fields' => ['status', 'id']]);
    }

    public function getNameClients()
    {

        $options['joins'] = [
            ['table' => 'order_billing_infos',
                'alias' => 'OrderBillingInfo',
                'type' => 'LEFT',
                'conditions' => [
                    'Order.id = OrderBillingInfo.id'
                ],
            ]
        ];

        $options['fields'] =[
            'OrderBillingInfo.*',
            'Order.*'
        ];
        return $this->find('all', $options);

//        return $this->OrderBillingInfo->find('all', ['order' => ['OrderBillingInfo.id' => 'desc']]);


    }

}
