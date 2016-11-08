<?php
App::uses('AdminAppController', 'Admin.Controller');
/**
 *@property Accessory $Accessory
 *@property
 */
class AdminAccessoriesController extends AdminAppController
{
    public $uses = ['Accessory'];

   public function index()
   {
       $this->setActiveMenu(array('products'));

       $product = $this->Accessory->find('all');
        $this->set([
            'product' => $product
        ]);
   }

   public function add()
   {
       $this->setActiveMenu(array('products'));

       if ($this->request->data){
            if($this->Accessory->save($this->request->data['Accessory'])){
                $this->setFlash('Service is created', 'success');
                $this->redirect(['action' => 'index']);
            }
        }
   }

   public function edit()
   {
       $this->setActiveMenu(array('products'));

       if ($this->request->data){
           $this->Accessory->save($this->request->data['Accessory']);
           $this->setFlash('Service is created', 'success');
           $this->redirect(['action' => 'index']);
       }
       $product = $this->Accessory->getOnId($this->request->id);

       $this->set([
          "product" => $product
       ]);
   }

   public function del()
   {
       $this->Accessory->delete($this->request->id);
       $this->setFlash('Service is created', 'success');
       $this->redirect(['action' => 'index']);
   }



}