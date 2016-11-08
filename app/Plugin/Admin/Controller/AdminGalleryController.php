<?php
App::uses('AdminAppController', 'Admin.Controller');
/**
 *@property Gallery $Gallery
 * @property  ProductRental $ProductRental
 */

class AdminGalleryController extends AdminAppController
{
    public $uses = ['Gallery'];


    public function index()
    {
        $this->setActiveMenu(array('blog'));

        $img = $this->Gallery->getAll();
        $this->set(['img' => $img]);
    }

    public function add()
    {
        $this->setActiveMenu(array('blog'));

        if ($this->request->is('post')){
            $this->Gallery->save($this->request->data);

        }
    }

    public function edit()
    {
        $this->setActiveMenu(array('blog'));

        if ($this->request->is('post')){
            $this->Gallery->save($this->request->data);
            $this->setFlash('Service is created', 'success');
            $this->redirect(['action' => 'index']);
        }

        $url_img = $this->Gallery->getImgOnId($this->request->id);

        $this->set([
           'img' => $url_img[0]
        ]);


    }

    public function del()
    {
        $this->Gallery->delete($this->request->id);
        $this->setFlash('Remove done', 'success');
        $this->redirect(['action' => 'index']);
    }

}