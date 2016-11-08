<?php
App::uses('AdminAppController', 'Admin.Controller');
/**
 *@property Product $Product
 * @property  ProductRental $ProductRental
 */

class AdminProductRentalsController extends AdminAppController
{
    public $uses = ['ProductRental', 'Product'];


    public function index()
    {

    $this->set([
        'product' => $this->Product->getAll()
    ]);

    }

    public function add()
    {
        //
    }

    public function edit()
    {
        if ($this->request->data){
            if ($this->ProductRental->saveWell($this->request->data['productRentals'])){
                if ( $this->ProductRental->updateTitle($this->request->data['productName'])){
                    $this->setFlash('Service is created', 'success');
                }
            } else{
                $this->setFlash('Slider is not saved', 'error');
            }
        };


        $this->set([
            'allInfoProductRent' => $this->ProductRental->findId($this->request->id),
            'product' => $this->ProductRental->getProductId($this->request->id)
        ]);
    }

    public function ajax($id)
    {
        $this->ProductRental->delete($this->request->data['id']);
    }

}