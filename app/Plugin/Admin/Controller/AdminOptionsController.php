<?php
App::uses('AdminAppController', 'Admin.Controller');

/**
 * @property Option $Option
 */

class AdminOptionsController extends AdminAppController {

    public $uses = array('Option');

    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->setHoverFlag('options');
    }

    public function index() {
        $this->set('list', $this->Option->find('all'));
        $this->setActiveMenu(array('options'));
    }

    public function edit($id = null) {
        $this->setActiveMenu(array('options'));
        if($this->request->data) {
            $this->Option->set($this->request->data);
            if($this->Option->save()) {
                $this->setFlash('Option successfully saved', 'success');
                $this->redirect(array('action' => 'index'));
            }else {
                $this->setFlash('Error! Can\'t save. Call to your administrator', 'error');
            }
        }
        $this->request->data = $this->Option->findById((int) $id);
        return true;
    }

    public function clear_cache() {
        $res = exec('cd ~/public_html && find ~/public_html/app/tmp/cache -type f -exec rm {} \; && rm -rf ~/public_html/app/tmp/cache/views/*;');
        $this->redirect($this->referer());
    }
}