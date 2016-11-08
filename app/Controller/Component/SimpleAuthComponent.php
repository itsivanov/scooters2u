<?php
App::uses('Component', 'Controller');

/**
 * Simple Authorization by roles
 *
 * @property AuthComponent $Auth
 * @property User $User
 * @property SessionComponent $Session
 */
class SimpleAuthComponent extends Component
{
    public $components = ['Session', 'Auth', 'Cookie'];

    public $publicActions = [];

    public $controller = null;

    public $groups = [];

    public $admin = '';

    private $config = [];

    public function initialize(Controller $controller, $settings = [])
    {
        $this->controller =& $controller;
    }

    /**
     * Initialize Auth component
     * @return void
     */

    public function initAuth()
    {
        Configure::load('permissions');
        $this->config = Configure::read('AuthPermissions');

        if (Configure::read("debug") == 0) {
            session_start();
        }
        $this->Auth->authenticate = [
            'Form' => [
                'fields' => ['username' => 'email'],
                'scope' => ['User.active' => 1]
            ]
        ];

        $this->Auth->loginRedirect = [
            'controller' => 'users',
            'action' => 'account'
        ];

        $this->Auth->logoutRedirect = [
            'controller' => 'pages',
            'action' => 'home'
        ];

        $this->Auth->loginAction = [
            'controller' => 'pages',
            'action' => 'home'
        ];

        $this->Auth->authError = 'You must be logged in to view this page';
        $this->Auth->loginError = 'Username / password is incorrect or account is inactive';


        $this->User = (ClassRegistry::getObject('User')) ?: ClassRegistry::init('User');

        // for anonimous users (authorized users used aka-ACL rules)

        if (!$this->Auth->user()) {
            $this->checkPublicAuth();
        } else {
//            $this->isAuth($this->Auth->user());
            $this->controller->userId = $this->Auth->user('id');
            $this->controller->set('isAuth',true);
        }
    }

    private function checkPublicAuth()
    {
        $available = false;

        $curControllerName = $this->controller->plugin ? "{$this->controller->plugin}.{$this->controller->name}" : $this->controller->name;
        foreach ($this->config['publicActions'] as $controller => $action) {
            if ($controller == $curControllerName) {
                if ($action == '*' || in_array($this->controller->action, $action)) {
                    $this->Auth->allow($this->controller->action);
                    $available = true;
                }
            }
        }

        if (!$available){
//            $this->controller->flashMsg('Please login', 'error');
            $this->controller->redirect('/');
        }
    }

    public function isAuth($user)
    {
        // Admin can access every action
        if (isset($user['group_id']) && $user['group_id'] == 1) {
            return true;
        }

        $this->publicActions = $this->config['publicActions'];
        $currentGroupName = '';
        foreach ($this->config['authGroups'] as $group => $options) {
            if ($options['group_id'] == $user['group_id']) {
                $currentGroupName = $group;
                break;
            }
        }

        $this->config['authGroups'][$currentGroupName]['accesses'] = array_merge($this->config['commonAuthAccess'], $this->config['authGroups'][$currentGroupName]['accesses']);
        $accesses = $this->config['authGroups'][$currentGroupName]['accesses'];
        $curControllerName = $this->controller->plugin ? "{$this->controller->plugin}.{$this->controller->name}" : $this->controller->name;
        if ($curControllerName == 'Users' && $this->controller->action == 'denied') {
            return true;
        }
        foreach ($accesses as $controller => $action) {
            if ($controller == $curControllerName) {
                if ($action == '*' || in_array($this->controller->action, $action)) {
                    return true;
                }
            }
        }

        // Default deny
        $this->flashMsg('Access not allowed!', 'error');
        $this->redirect('/');

        return false;
    }
}