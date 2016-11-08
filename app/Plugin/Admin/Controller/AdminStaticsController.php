<?php
//App::uses('AdminAppController', 'Admin.Controller');
///**
// *@property Static $Static
// */
//class AdminStaticsController extends AdminAppController
//{
//    public $uses = ['Static'];
//    public $helpers = array('Admin.ExtTree');
//
//    const ACTIVE = 1;
//    const INACTIVE = 0;
//
//    public function beforeFilter() {
//        parent::beforeFilter();
//        $this->setHoverFlag('content');
//        $this->setActiveMenu(array('content'));
//    }
//
//    public function edit($id)
//    {
//        if ($this->request->data) {
//            $data = $this->request->data;
//            $data['Static']['id'] = $id;
//            if ($this->Static->save($data)) {
//                $this->setFlash('Content is saved', 'success');
//            } else {
//                $this->setFlash('Content is not saved', 'error');
//            }
//        }
//        $data = $this->Static->read('', $id);
//        $this->request->data = $data;
//
//        $this->set(compact('data'));
//        $this->render($id);
//    }
//
//}