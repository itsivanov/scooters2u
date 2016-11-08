<?php
/**
 * @property AuthComponent $Auth
 * @property MessengerComponent $Messenger
 * @property User $User
 */

class AdminAppController extends AppController
{
    public $helpers = ['Admin.simpleNotify', 'Session', 'Html', 'Form'];
    public $viewClass = 'TwigView.Twig';
    public $uses = [
        'SiteMenu',
        'Admin.Contact',
        'User',
        'Order',
        'Category'
    ];

    public $components = [
        'Auth' => [
            'loginRedirect'  => false,
            'logoutRedirect' => false,
            'authError'      => 'You must be logged in to view this page.',
            'loginError'     => 'Username or password is incorrect.',
            'loginAction' => [
                'controller' => 'users',
                'action'     => 'login',
                'plugin'     => 'admin'
            ],
            'authenticate'   => [
                'Form' => [
                    'fields' => ['username' => 'username'],
                    'userModel' => 'Admin.User'
                ]
            ]
        ],
        'Paginator' => [
            'settings' => [
                'limit' => 10,
                'order' => ['id' => 'desc']
            ]
        ]
    ];

    public function beforeFilter()
    {
        $this->initAuth();
    }

    public function beforeRender()
    {
        $newMsg = $this->Contact->countNewMessages();
        $newOrders = 0; // TODO: $this->Order->countNewOrders();

        $menuCategories = $this->Category->find('all', ['order' => ['id' => 'asc']]);

        $this->set(compact('newMsg', 'newOrders', 'menuCategories'));

        parent::beforeRender();
    }

    private function initAuth()
    {
        if ($this->Session->check('Auth.User')) {
            if ($this->Session->read('Auth.User.group_id') != 1) {
                $this->Session->setFlash('Access denied', 'pnotify', ['type' => 'error']);
                $this->redirect('/');
            }
        }
    }

    protected function setActiveMenu($menuNames)
    {
        $activeItem = (is_array($menuNames)) ? $menuNames : [$menuNames];
        $this->set('activeItem', $activeItem);
    }

	/**
	 * Set left menu name
	 * @param string $menu_name
	 */
	protected function setLeftMenu($menu_name)
	{
		$this->set('_left_menu_name', array($menu_name => true));
	}

    /**
     * Set menu hover flag
     * @param $menu_name - Name of hover menu
     */
	protected function setHoverFlag($menu_name)
	{
		$this->set('_hovers', array($menu_name => true));
	}

	/**
	 * Set jGrowl message (for use jGrowl helper)
	 * @param string $msg Message to be flashed
	 * @param string $type Type of message (error, warning, success, message). Default is 'message'
	 * @param string $key Message key, default is 'flash'
	 */

	protected function setFlash($msg, $type = 'message', $key = 'flash')
	{
		$types = ['error', 'warning', 'message', 'success'];
        $title = false;

		if(!in_array($type, $types)) {
			$type = 'message';
		}

		if(empty($key)) {
			$key = 'flash';
		}
        if (is_array($msg)) { $title = $msg['title']; $msg = $msg['msg']; }
		$flash = array(
			'type' => $type,
            'title' => $title,
			'message' => __($msg, true)
		);

		if(CakeSession::check('FlashMessage.' . $key)) {
			$flashData = CakeSession::read('FlashMessage.' . $key);

			array_push($flashData, $flash);
		} else {
			$flashData[] = $flash;
		}

		CakeSession::write('FlashMessage.' . $key, $flashData);
	}

    function saveSorting($model)
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $err = false;
        $err_desc = '';

        if (isset($this->data['items']) && !empty($this->data['items'])) {
            if (!($this->{$model}->saveSort($this->data['items']))) $err_desc = 'An error occurred when saving items!';
        }

        if (!empty($err_desc)) $err = true;
        $result = array(
            'error' => $err,
            'err_desc' => $err_desc,
        );

        exit(json_encode($result));
    }

    public function ajaxDelete($model, $id) {
        Configure::write("debug", 0);
        $this->loadModel("{$model}");
        if(empty($id) && !is_numeric($id)) {
            return false;
        }

        if($this->$model->delete($id)) {
            exit("okey");
        }
        return false;
    }

    public function getUnconfirmedContent($limit = null)
    {
        if ($limit){
            $limit = ' LIMIT '.$limit;
        }
        $list = $this->User->query('SELECT * FROM  `vt_users` User
                                    LEFT JOIN `vt_user_infos` AS UserInfo ON (UserInfo.user_id = User.id)
                                    LEFT JOIN `vt_user_images` AS MainImage ON (MainImage.user_id = User.id AND MainImage.main = 1)
                                    WHERE
                                    (
                                        SELECT COUNT( * )
                                        FROM  `vt_new_user_infos` i
                                        WHERE User.id = i.user_id >0
                                    ) OR(
                                        SELECT COUNT( * )
                                        FROM  `vt_user_images` g
                                        WHERE User.id = g.user_id
                                        AND g.active =0 >0
                                    ) OR (
                                        SELECT COUNT( * )
                                        FROM  `vt_user_videos` v
                                        WHERE User.id = v.user_id
                                        AND v.active =0 >0
                                    )
                                    ORDER BY User.id DESC
                                    '.$limit);

//        OR (
//    SELECT COUNT( * )
//                                        FROM  `vt_calendars` c
//                                        WHERE User.id = c.user_id
//    AND c.active =0 >0
//                                    )

        return $list;
    }

}