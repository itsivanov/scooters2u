<?php
App::uses('AdminAppController', 'Admin.Controller');
/**
 * @property  Slider $Slider
 *@property Product $Product
 */

class AdminSlidesController extends AdminAppController
{
    public $uses = [
        'Slider',
        'Product'
    ];

    /**
     * Show active elements for sliders
     */
    public function index()
    {
        $this->setActiveMenu(array('content'));

        $this->set([
            'allSlides' => $this->Slider->findAllSliders()
        ]);
    }

    /**
     *  Add slider
     */
    public function add()
    {
        $this->setActiveMenu(array('content'));

        if ($this->request->data){
           $this->Slider->save($this->request->data);
           $this->setFlash('Service is created', 'success');
           $this->redirect(['action' => 'index']);
        }

    }
    /**
     * Edit sliders
     */
    public function edit()
    {

        $this->setActiveMenu(array('content'));

        if ($this->request->data){
            if ($this->Slider->update($this->request->data)){
                $this->setFlash('Service is created', 'success');
                $this->redirect(['action' => 'index']);
            } else{
                $this->setFlash('Slider is not saved', 'error');
            }
        }
        $this->set([
            'allSlides' => $this->Slider->findId($this->request->id)
        ]);
    }

    /**
     * Delete sliders
     */
    public function del()
    {
        $this->Slider->delete($this->request->id);
        $this->setFlash('Remove done', 'success');
        $this->redirect(['action' => 'index']);

    }
}