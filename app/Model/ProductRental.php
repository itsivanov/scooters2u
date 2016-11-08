<?php
App::uses('AppModel', 'Model');

/**
 * Product Model
 *
 */

class ProductRental extends AppModel {

    public $name = "ProductRental";
    public $use = ['Product'];
    public $recursive = -1;

    public $belongsTo = [
        'Product'
    ];

    /**
     *  Edit of admin panel
     * @param $data array
     * @return boolean
     */
    public function saveWell($data)
    {
        if (!empty($data)){
            foreach ($data as $v){
                if (empty($v['value'])){
                    $v['value'] = 0;
                }
                $this->saveAll($v);
            }
        }

        return true;
    }

    /**
     *
     * @return array
     */

    public function getListProduct()
    {
         $options['joins'] = [
            ['table' => 'products',
                'alias' => 'Product',
                'type' => 'LEFT',
                'conditions' => [
                    'ProductRental.product_id = Product.id'
                ],
            ]
         ];

        $options['fields'] =[
            'Product.*',
            'Product.*'
        ];

        return $this->Product->find('all');
    }

    /**
     * @param $id
     * @return array
     */
    public function findId($id)
    {
        return $this->find('all', [
            'conditions' => [
                'product_id' => $id
            ]
        ]);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getProductId($id)
    {
        return $this->Product->find('first', [
            'conditions' => [
                'id' => $id
            ]
        ]);
    }

    /**
     * @param $data
     * @return mixed
     */
    public function updateTitle($data)
    {
        $this->Product->id = $data['id'];
        return $this->Product->saveField('title',$data['title'] );
    }

    public function deleteRent($id)
    {
//        $this->ProductRental->product_id = intval($id);
        $this->deleteAll([
            'ProductRental.product_id' => intval($id)
        ]);
    }

    
}
