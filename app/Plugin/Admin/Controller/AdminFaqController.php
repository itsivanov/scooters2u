<?php
App::uses('AdminAppController', 'Admin.Controller');
/**
 *@property Faq $Faq
 * @property  ProductRental $ProductRental
 */

class AdminFaqController extends AdminAppController
{
    public $uses = ['Faq'];


    public function index()
    {
        $this->setActiveMenu(array('blog'));

        $this->set([
           'questions' => $this->Faq->getAll()
        ]);

    }

    public function add()
    {
        $this->setActiveMenu(array('blog'));

        if ($this->request->is('post')){
            $this->Faq->save($this->request->data);
            $this->setFlash('Service is created', 'success');
            $this->redirect(['action' => 'index']);


        }
    }

    public function edit()
    {
        $this->setActiveMenu(array('blog'));

        if ($this->request->data){
            $this->Faq->save($this->request->data);
            $this->setFlash('Service is created', 'success');
                $this->redirect(['action' => 'index']);

        }
        $faq = $this->Faq->getOnId($this->request->id);

        foreach ($faq as $items) {
            foreach ($items as $item) {
                $arrFaq = $item;
            }
        }

        $this->set(['faq' => $arrFaq]);
    }

    public function del()
    {
        $this->Faq->delete($this->request->id);
        $this->setFlash('Remove done', 'success');
        $this->redirect(['action' => 'index']);
    }

}