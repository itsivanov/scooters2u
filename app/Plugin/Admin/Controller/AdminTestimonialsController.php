<?php
App::uses('AdminAppController', 'Admin.Controller');
/**
 *@property Service $Service
 */
class AdminTestimonialsController extends AdminAppController
{
    public $uses = ['Testimonial'];
    public $helpers = array('Admin.ExtTree');

    const ACTIVE = 1;
    const INACTIVE = 0;

    public function beforeFilter() {
        parent::beforeFilter();
        $this->setHoverFlag('testimonials');
        $this->setActiveMenu(array('testimonials'));
    }

    public function index()
    {
        $list = $this->Testimonial->find('all');
        $this->set('list', $list);
    }

    public function add()
    {
        if ($this->request->data) {
            if ($this->Testimonial->save($this->request->data)) {
                $this->setFlash('Service is created', 'success');
                $this->redirect(array('action' => 'index'));
            } else {
                $this->setFlash('Service is not created', 'error');
            }
        }
    }

    public function edit($id)
    {
        if ($this->request->data) {
            $this->Testimonial->id = $id;
            if ($this->Testimonial->save($this->request->data)) {
                $this->setFlash('Service is saved', 'success');
                $this->redirect(array('action' => 'index'));
            } else {
                $this->setFlash('Category is not saved', 'error');
            }
        }
        $data = $this->Testimonial->read('', $id);
        $this->request->data = $data;

        $this->set(compact('data'));
        $this->render('add');
    }

    public function delete($id) {
        if ($this->Testimonial->delete($id)) {
            $this->setFlash('Service is deleted', 'success');
        } else {
            $this->setFlash('Service is not deleted', 'error');
        }
        $this->redirect($this->referer());
    }

}