<?php
App::uses('AdminAppController', 'Admin.Controller');
/**
 *@property RentalFee $RentalFee
 * @property RentalFeeOption $RentalFeeOption
 */

class AdminRentalsFeesController extends AdminAppController
{
    public $uses = ['RentalFee', 'RentalFeeOption'];


    public function index()
    {
        $this->setActiveMenu(array('content'));

        $listRentFee = $this->RentalFee->getTList();
        $this->set([
            'listRentFee' => $listRentFee
        ]);

    }


    public function edit()
    {
        $this->setActiveMenu(array('content'));

        if ($this->request->data){
            $rew = [];
            foreach ($this->request->data['RentalFeeOption'] as $item) {
                $rew[] = $item;
            }
            $this->RentalFee->save($this->request->data['RentalFee']);
            $this->RentalFeeOption->deleteAll([
                'RentalFeeOption.rental_fee_id' => intval($this->request->id)
            ]);

            foreach ($this->request->data['RentalFeeOption'] as $item) {
                $this->RentalFeeOption->saveAll($item);
            }
            $this->setFlash('Service is created', 'success');
        }

        $arrMain = [];
        $allRentFee = $this->RentalFeeOption->getAll($this->request->id);
        $arrMain['RentalFee'] = $allRentFee[0]['RentalFee'];
        foreach ($allRentFee as $items) {
            $arrMain['RentalFeeOption'][] = $items['RentalFeeOption'];
        }
        $this->set([
            'allInfo' => $arrMain,
        ]);
    }

    public function ajax()
    {
        $this->RentalFeeOption->delete($this->request->data['id']);
    }

}