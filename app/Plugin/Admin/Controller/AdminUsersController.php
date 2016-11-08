<?php
App::uses('AdminAppController', 'Admin.Controller');
/**
 * Users Controller
 * @property Category $Category
 *
 */
class AdminUsersController extends AdminAppController
{
    public $uses = [
        'User',
        'Category',
        'Order'
    ];

    const ACTIVE   = 1;
    const INACTIVE = 0;


    public function beforeFilter() {
        parent::beforeFilter();
        $this->setHoverFlag('users');
        $this->setActiveMenu(array('users'));
    }

    public function login()
    {
        $this->layout = 'login';

        $err = false;
        $errDesc = '';
        if ($this->request->is('ajax')) {
            Configure::write('debug', 0);
            $this->autoRender = false;

            if (!$this->Auth->login()) {
                $errDesc = 'Username or password is incorrect';
                $err = true;
            }

            $result = array(
                'error' => $err,
                'err_desc' => $errDesc,
            );
            exit(json_encode($result));
        }
    }

    public function logout()
    {
        $this->redirect($this->Auth->logout());
    }

    public  function index()
    {

        $settings  = [
            'limit' => 10,
            'order' => ['id' => 'desc'],
            'contain' => [
                'UserInfo' => [
                    'first_name',
                    'last_name'
                ]
            ],
            'conditions' => [
                'User.group_id != ' => 1
            ]
        ];

        $this->Paginator->settings = $settings;

        $list = $this->Paginator->paginate('User');
        $this->set('list', $list);
    }

//    public function add()
//    {
//        if (isset($this->request->data['User'])){
//            $this->request->data['User']['password'] = Security::hash($this->request->data['User']['password'], null, true);
//            if ($this->User->saveAll($this->request->data)){
//                $this->setFlash('User is created', 'success');
//                $this->redirect('/admin/users/edit/'.$this->User->getLastInsertID());
//                $this->sendNotification('agentAddNewUser', $this->User->getLastInsertID());
//            }
//        }
//
//        $this->set([
//            'userTypes' => $this->groupsArr
//        ]);
//    }

    public  function edit($id)
    {
        if ($this->request->data) {
            if ($this->User->saveAll($this->request->data)) {
                $this->setFlash('User is saved', 'success');
            } else {
                $this->setFlash('User is not saved', 'error');
            }
        }

        $this->request->data = $this->User->read('', $id);

        $settings = [
            'conditions' => ['Order.user_id' => $id],
            'contain' => [
                'OrderStatus',
                'OrderItem'
            ]
        ];

        $this->loadModel('State');

        $this->set([
            'orderList' => $this->Order->find('all', $settings),
            'states' => $this->State->find('list', ['fields' => ['name', 'name'], 'order' => ['name' => 'asc']]),
            ]
        );
    }

    public  function delete($id)
    {
        if ($this->User->delete($id)) {
            $this->setFlash('User is deleted', 'success');
            $this->redirect('/admin/users');
        } else {
            $this->setFlash('User is not deleted', 'error');
            $this->redirect($this->referer());
        }
    }

    public function password()
    {
        $this->layout = 'default';
        if($this->data) {
            if($this->User->saveAdminPassword($this->data)) {
                $this->setFlash('Password is changed', 'success');
//                $this->redirect($this->Auth->logout());
            } else {
                $this->setFlash($this->formatErrors($this->User->validationErrors), 'error');
            }
        }
        $this->setHoverFlag('users');
        $this->setLeftMenu('password');
    }
}