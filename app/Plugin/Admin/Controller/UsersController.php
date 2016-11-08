<?php
App::uses('AdminAppController', 'Admin.Controller');
/**
 * Users Controller
 *
 */
class UsersController extends AdminAppController
{
    public $uses = ['AdminUser'];

    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->layout = 'login';
    }

    public function login()
    {
        $err = false;
        $errDesc = '';
        $redirectURL = '';
        if ($this->request->is('post')) {
            if ($this->request->is('ajax')) {
                Configure::write('debug', 0);
                $this->autoRender = false;

                if ($this->Auth->login()) {
                    $redirectURL = $this->Auth->redirect();
                } else {
                    $errDesc = 'Username or password is incorrect';
                }

                if (!empty($errDesc)) $err = true;
                $result = array(
                    'callbackURL' => $redirectURL,
                    'error' => $err,
                    'err_desc' => $errDesc,
                );
                exit(json_encode($result));
            }
        }
    }

    public function logout()
    {
        $this->redirect($this->Auth->logout());
    }

    public function password()
    {
        $this->layout = 'default';
        if($this->data) {
            if($this->User->savePassword($this->data)) {
                $this->setFlash('Password is changed', 'success');
                $this->redirect($this->Auth->logout());
            }
        }
        $this->setHoverFlag('users');
        $this->setLeftMenu('password');
    }

    public function email()
    {
        if($this->data) {
            $this->User->id = 1;
            if($this->User->saveField('email', $this->data['User']['email'])){
                $this->setFlash('E-mail address is changed', 'success');
            }
        }
        $this->setLeftMenu('email');
        $this->setHoverFlag('users');
        $user = $this->User->findById(1, 'email');
        $this->set('email', $user['User']['email']);
    }
}