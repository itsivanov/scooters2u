<?php
App::uses('AdminAppController', 'Admin.Controller');

/**
 * Pages Controller
 *
 */
class AdminClaimsController extends AdminAppController
{

    public $uses = array('Claim', 'Category', 'Service');
    public $helpers = array('Admin.ExtTree');
    public $components = array('Paginator');

    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->setActiveMenu(array('сlaims'));
    }

    public function index()
    {

        $this->Paginator->settings = array(
            'contain' => array(),
            'order' => array(
                'Claim.lft' => 'asc'
            ),
            'recursive' => -1,
            'limit' => 6
        );
        $Claims = $this->Paginator->paginate('Claim');

        $claims = $this->Claim->get();
        $this->set('claims', $claims);
    }

    public function edit($id = null)
    {
        $id = (int)$id;
        if ($id !== 0) {
            if ($this->request->data) {
                if ($this->Claim->saveAll($this->request->data)) {
                    $this->setFlash('Claim is saved', 'success');
                } else {
                    $this->setFlash('Claim is not saved', 'error');
                }
            }
            $data = $this->Claim->read('', $id);

            $this->request->data = $data;

            $this->set(array(
                'services'   => $this->Service->getHighLevelServices(),
                'categories' => Set::extract('/Category/.', $this->Category->find('all')),
                'title'      => 'Edit claim'
            ));

            $this->render('add');
        } else {
            $this->setFlash(array('msg' => 'Claim id is incorrect'), 'error');
            $this->redirect(array('action' => 'index'));
        }
    }

    public function add()
    {
        if ($this->request->data) {
            if ($this->Claim->saveAll($this->request->data)) {
                $this->setFlash(array('msg' => 'Claim is created'), 'success');
                $this->redirect(array('action' => 'index'));
            } else {
                $this->setFlash('Claim is not saved', 'error');
                $this->redirect(array('action' => 'index'));
            }
        } else {
            $this->set(array(
                'services'   => $this->Service->getHighLevelServices(),
                'categories' => Set::extract('/Category/.', $this->Category->find('all')),
                'title'      => 'Add claim'
            ));
            $this->render('add');
        }
    }


    public function delete($id)
    {
        if ($this->Claim->delete($id)) {
            $this->setFlash(array('msg' => 'Claim is deleted'), 'success');
        } else {
            $this->setFlash('Page is not deleted', 'error');
        }
        $this->redirect(array('action' => 'index'));
    }
}
