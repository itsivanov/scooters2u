<?php
App::uses('AppController', 'Controller');
/**
 * Users Controller
 *
 * @property User $User
 * @property Group $Group
 *
 */
class UsersController extends AppController {

    public function beforeFilter() {
        parent::beforeFilter();
    }

    public function index()
    {

    }

    public function login()
    {
        if ($this->request->is('ajax') && isset($this->request->data['User']) && !$this->Auth->User()) {
            if ($this->Auth->login()) {
                $this->User->updateLastUserLogin($this->Auth->user('id'));
//                $this->flashMsg('Successful entrance', 'success');
                $afterLogin = $this->Session->check('Auth.redirect') ? $this->Session->read('Auth.redirect') : $this->Auth->redirectUrl();

                $this->GoodAjax->ajaxResponse['url'] = $afterLogin;
                $this->GoodAjax->ajaxResponse['user'] = $this->Auth->User();
            } else {
                $this->GoodAjax->ajaxResponse['error'] = $this->Auth->loginError;
            }
        }

        $this->GoodAjax->sendAjax();
    }

    public function register()
    {
        if($this->Auth->User()) {
            $this->redirect(['controller' => 'users', 'action' => 'account']);
        }

        if (isset($this->request->data['User'])) {
            $this->request->data['User']['group_id'] = 2;
            $this->request->data['User']['confirm_password'] = $this->request->data['User']['password'];
            if ($this->request->is('ajax') && $this->request->data['User']['agree'] != 1) {
                $this->GoodAjax->ajaxResponse['errorDesc'] = 'You need to agree with term of use to register';
                $this->GoodAjax->sendAjax();
            }
            if ($this->User->save($this->request->data)) {
                $this->Messenger->register($this->request->data);
                $this->Auth->login();
                if ($this->request->is('ajax')) {
                    $this->GoodAjax->ajaxResponse['url'] = '/users/account';
                    $this->GoodAjax->ajaxResponse['user'] = $this->Auth->User();
                    $this->GoodAjax->sendAjax();
                } else {
                    $this->redirect($this->Auth->loginRedirect);
                }

            } else {
                if ($this->request->is('ajax')) {
                    $this->GoodAjax->ajaxResponse['errorDesc'] = $this->formatErrors($this->User->validationErrors);
                    $this->GoodAjax->sendAjax();
                }
            }
        }
    }

    public function logout()
    {
//        $this->redirect($this->Auth->logout());
        $this->Auth->logout();
        $this->redirect('/');
    }

    public function account()
    {
        if (isset($this->request->data['User'])){
            if ($this->request->data['User']['id'] == $this->userId) {
                if ($this->User->saveAll($this->request->data)){
                    $this->flashMsg('Your profile was updated');
                }
            }
        }

        $user = $this->User->getUserBy($this->userId, array('UserInfo', 'UserBillingInfo', 'UserShippingInfo'));
        unset($user['User']['password']);
        $this->request->data = $user;

        $this->loadModel('State');

        $this->set([
            'states' => $this->State->find('list', ['fields' => ['name', 'name'], 'order' => ['name' => 'asc']]),
            'account' => true

        ]);
    }

    public function orders()
    {
        $this->loadModel('Order');
        $this->set([
            'orders' => true,
            'list' => $this->Order->findListByUser($this->userId)
        ]);
    }

    public function rewards()
    {
        $potgCoefficient = ClassRegistry::init('Option')->getByKey('ptod_coefficient');

        $this->set([
            'rewards' => true,
            'user' => $this->User->getUserBy($this->userId),
            'potgCoefficient' => $potgCoefficient
        ]);
    }

    public function forget()
    {
        if($this->Auth->User()) {
            $this->redirect(['controller' => 'users', 'action' => 'account']);
        }

        $email = isset($this->request->data['User']['email']) ? $this->request->data['User']['email'] : null;

        if ($email){
            $user = $this->User->getUserBy($email);
            if ($user) {
                $password = substr(md5(microtime()), 3, 7);

                $data = [
                    'id' => $user['User']['id'],
                    'password' => $password
                ];

                if($this->User->save($data, false)) {
                    $email = [
                        'to' => $this->request->data['User']['email'],
                        'subject' => 'Password recovery - '.$_SERVER['SERVER_NAME'],
                        'vars' => [
                            'password' => $password,
                        ]
                    ];

                    $this->sendEmail($email);
                }
                $this->flashMsg('Check your email', 'info');
            }else {
                $this->flashMsg('Email not found', 'warning');
            }
        }
    }

    public function reset()
    {
//        if($this->Auth->User()) {
//            $this->redirect(['controller' => 'users', 'action' => 'account']);
//        }

        $email = isset($this->request->data['User']['email']) ? $this->request->data['User']['email'] : null;

        if ($email){
            $user = $this->User->getUserBy($email);
            if ($user) {
                $password = substr(md5(microtime()), 3, 7);

                $data = [
                    'id' => $user['User']['id'],
                    'password' => $password
                ];

                try {
                    if ($this->User->save($data, false)) {
                        $email = [
                            'to' => $this->request->data['User']['email'],
                            'subject' => 'Password recovery - ' . $_SERVER['SERVER_NAME'],
                            'template' => 'reset',
                            'vars' => [
                                'password' => $password,
                            ]
                        ];

                        $this->sendEmail($email);
                    }
                    $this->GoodAjax->ajaxResponse['message'] = 'Check your email';
                } catch (Exception $e) {
                    $this->GoodAjax->ajaxResponse['errorDesc'] = 'Email cannot be send right now. Please try later or contact us';
                }
                $this->GoodAjax->sendAjax();
            }else {
                $this->GoodAjax->ajaxResponse['errorDesc'] = 'Email not found';
                $this->GoodAjax->sendAjax();
            }
        }
        $this->GoodAjax->ajaxResponse['errorDesc'] = 'Provide email, please';
        $this->GoodAjax->sendAjax();
    }

    public function feedback()
    {
        /** @var Feedback $Feedback */
        $Feedback = ClassRegistry::init('Feedback');

        if ($this->request->is('ajax')) {
            if ($this->request->data) {

                if (!$this->userId) {
                    $this->GoodAjax->ajaxResponse['errorDesc'] = 'Please login';
                    $this->GoodAjax->sendAjax();
                }

                if(!$this->Session->check('feedbackOrderId')){
                    $this->GoodAjax->ajaxResponse['errorDesc'] = 'Please checkout new order';
                    $this->GoodAjax->sendAjax();
                }

                $this->request->data['Feedback']['user_id'] = $this->userId;
                $this->request->data['Feedback']['order_id'] = $this->Session->read('feedbackOrderId');

                if ($this->request->data['Feedback']['like'] == 'other') {
                    $this->request->data['Feedback']['like'] = $this->request->data['Feedback']['like_other'];
                }

                if ($this->request->data['Feedback']['improve'] == 'other') {
                    $this->request->data['Feedback']['improve'] = $this->request->data['Feedback']['improve_other'];
                }

                if (!$Feedback->saveAll($this->request->data)) {
                    $this->GoodAjax->ajaxResponse['errorDesc'] = $this->formatErrors($Feedback->validationErrors);
                    $this->GoodAjax->sendAjax();
                }

                $this->Session->delete('feedbackOrderId');

                $this->GoodAjax->ajaxResponse['message'] = 'Success';
                $this->GoodAjax->sendAjax();

            }
        }

        $this->redirect($this->referer('', true));
    }
}