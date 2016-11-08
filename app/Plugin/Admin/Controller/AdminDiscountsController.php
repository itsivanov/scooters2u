<?php
App::uses('AdminAppController', 'Admin.Controller');
/**
 *@property Discount $Discount
 */
class AdminDiscountsController extends AdminAppController
{
    public $uses = ['Discount'];

    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->setHoverFlag('discounts');
        $this->setActiveMenu(array('discounts'));

        $this->set([
            'couponTypes' => $this->Cart->couponTypes
        ]);
    }

    public function index()
    {
        $list = $this->Discount->find('all');
        $this->set('list', $list);
    }

    public function add()
    {
        if ($this->request->data) {
            $data = $this->request->data['Discount'];

            $data['from_date'] = date('Y-m-d h:i:s', strtotime($data['from_date']));
            $data['to_date'] = date('Y-m-d h:i:s', strtotime($data['to_date']));

            if ($this->Discount->save($data)) {
                $this->setFlash('Discount is created', 'success');
                $this->redirect(array('action' => 'index'));
            } else {
                $this->setFlash('Discount is not saved', 'error');
            }
        }
        $this->render('edit');
    }

    public function edit($id)
    {
        if ($this->request->data) {
            $data = $this->request->data['Discount'];

            $data['from_date'] = date('Y-m-d h:i:s', strtotime($data['from_date']));
            $data['to_date'] = date('Y-m-d h:i:s', strtotime($data['to_date']));

//            var_dump($data);die();

            $this->Discount->id = $id;
            if ($this->Discount->save($data)) {
                $this->setFlash('Discount is saved', 'success');
                $this->redirect(array('action' => 'index'));
            } else {
                $this->setFlash('Discount is not saved', 'error');
            }
        }

        $data = $this->Discount->read('', $id);
        $data['Discount']['from_date'] = date('M-d-Y', strtotime($data['Discount']['from_date']));
        $data['Discount']['to_date'] = date('M-d-Y', strtotime($data['Discount']['to_date']));
        $this->request->data = $data;
    }

    public function delete($id) {
        if ($this->Discount->delete($id)) {
            $this->setFlash('Discount is deleted', 'success');
        } else {
            $this->setFlash('Discount is not deleted', 'error');
        }
        $this->redirect($this->request->referer());
    }
}