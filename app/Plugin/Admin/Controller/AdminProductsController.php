<?php
App::uses('AdminAppController', 'Admin.Controller');
/**
 *@property Product $Product
 * @property ProductRental $ProductRental
 */

class AdminProductsController extends AdminAppController
{
    public $uses = ['Product','ProductRental'];
    public $hasOne = 'ProductRental';
    const ACTIVE = 1;
    const INACTIVE = 0;

    public $hasAndBelongsToMany = array(
        'ProductRental',
    );


    public function index()
    {
        $this->setActiveMenu(array('products'));

        $list = $this->Product->getAll();

        $this->set([
            'listProduct' => $list
        ]);
    }

    /**
     *   Product adding to rent
     *
     */

    public function add()
    {
        if ($this->request->is('post')){

           $this->Product->save($this->request->data);
           $getLastInsertID = $this->Product->getLastInsertID();

            for ($i = 1; $i <= count($this->request->data['productRentals']); $i++ ){
                $this->request->data['productRentals'][$i]['product_id'] = intval($getLastInsertID);
                $this->request->data['productRentals'][$i]['value'] = floatval($this->request->data['productRentals'][$i]['value']);
            }
            $this->ProductRental->saveWell($this->request->data['productRentals']);
            $this->redirect(['action' => 'index']);

        }
    }

    public function edit()
    {
        if ($this->request->data){
            $this->Product->save($this->request->data['Product']);

            if(!empty($this->request->data['productRentals'])){
                for ($i = 1; $i <= count($this->request->data['productRentals']); $i++ ){
                    $this->request->data['productRentals'][$i]['product_id'] = intval($this->request->id);
                }
            }

            $this->ProductRental->deleteRent($this->request->id);

            if(!empty($this->request->data['productRentals'])){
                $arrRental = [];
                foreach ($this->request->data['productRentals'] as $productRental) {
                    if(!empty($productRental['value'])){
                        $arrRental[] = $productRental;
                    }
                }
                if($this->ProductRental->saveAll($arrRental)){
                    $this->setFlash('Service is created', 'success');
                }
            }


        }

        $arrData = [];

        $arr = $this->ProductRental->findId($this->request->id);
        foreach ( $arr as $items) {
            foreach ( $items as $item) {
                $arrData[] = $item;
            }
        }

        $this->set([
            'productInfo' => $this->Product->getProduct($this->request->id),
            'rentalInfo' => $arrData
        ]);
    }

    public function activate_proposition($id)
    {
        $this->Product->id = $id;
        $page = $this->Product->read('active');

        if ($page['Product']['active'] == self::ACTIVE) {
            $this->Product->saveField('active', self::INACTIVE);
            $this->setFlash('Proposition is blocked', 'success');
        } else {
            $this->Product->saveField('active', self::ACTIVE);
            $this->setFlash('Proposition is active', 'success');
        }
        $this->redirect($_SERVER[HTTP_REFERER]);

    }

    public function activate_sale ($id)
    {
        $this->Product->id = $id;
        $page = $this->Product->read('on_sale');

        if ($page['Product']['on_sale'] == self::ACTIVE) {
            $this->Product->saveField('on_sale', self::INACTIVE);
            $this->setFlash('Sales is blocked', 'success');
        } else {
            $this->Product->saveField('on_sale', self::ACTIVE);
            $this->setFlash('Sales is active', 'success');
        }
        $this->redirect($_SERVER[HTTP_REFERER]);
    }

    public function del()
    {
        $this->Product->delete($this->request->id);
        $this->setFlash('Remove done', 'success');
        $this->redirect(['action' => 'index']);
    }






}