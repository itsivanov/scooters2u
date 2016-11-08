<?php

/**
 * Created by PhpStorm.
 * User: root
 * Date: 26.07.16
 * Time: 17:17
 */
class RentalFeeOption extends AppModel
{
//    public $belongsTo = 'RentalFee';

    public $use = [
        'RentalFee'
    ];

    public function getAll($id)
    {
        $options['joins'] = [
            ['table' => 'rental_fees',
                'alias' => 'RentalFee',
                'type' => 'LEFT',
                'conditions' => [
                    'RentalFeeOption.rental_fee_id = RentalFee.id' ,
                ],
            ]
        ];

        $options['fields'] =[
            'RentalFee.*',
            'RentalFeeOption.*'
        ];

        $options['conditions']['RentalFee.id'] = $id;

        return $this->find('all', $options);
    }
}