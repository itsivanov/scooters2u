<?php
App::uses('AppModel', 'Model');

/**
 * Created by PhpStorm.
 * User: root
 * Date: 26.07.16
 * Time: 17:17
 */
class RentalFee extends AppModel
{
    public $hasMany = [
        'RentalFeeOption'
    ];


    /**
     *  Get all info of db rental_fees & rental_otions
     * @return array
     *
     */
    public function getAll($id)
    {
        $options['joins'] = [
            ['table' => 'rental_fee_options',
                'alias' => 'RentalFeeOption',
                'type' => 'LEFT',
                'conditions' => [
                    'RentalFee.id = RentalFeeOption.rental_fee_id',
                ],
            ]
        ];

        $options['fields'] =[
            'RentalFee.*',
            'RentalFeeOption.*'
        ];

//        $options['conditions']['RentalFee.id'] = $id;

        return $this->find('all', $options);
    }

    public function getTList()
    {
        return $this->find('all');

    }
}