<?php
App::uses('AdminAppController', 'Admin.Controller');
/**
 * Pages Controller
 *
 */
class AdminPagesController extends AdminAppController
{
    public $uses = array('Admin.AdminPage', 'User','Page');

    const ACTIVE = 1;
    const INACTIVE = 0;

  public $filterPeriods = [
      0 => 'today',
      3 => 'Last 3 days',
      7 => 'Last week',
      30 => 'Last month'
  ];


    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->setHoverFlag('pages');
        $this->setActiveMenu(['pages']);
    }

    public function dashboard()
    {
        $this->setActiveMenu(array('dashboard'));

        $period = isset($this->request->query['period']) ? $this->request->query['period'] : 0;

        $date = DateTime::createFromFormat('Y-m-d', date('Y-m-d'));
        $date->modify("-{$period} day");

        $orderStatus = $this->Order->getStatusesListCMS();

        $userSettings  = [
            'conditions' => [
                'User.created >=' => $date->format('Y-m-d')
            ],
            'fields' => [
                'id', 'point_balance'
            ],
            'contain' =>[
                'Order' => [
                    'fields' => [
                        'id',
                        'amount',
                        'used_poins',
                        'order_status_id'
                    ]
                ],
            ]
        ];

        $list = $this->User->find('all',$userSettings);

        $orderSettings  = [
            'conditions' => [
                'Order.created >=' => $date->format('Y-m-d')
            ],
//            'contain' =>[
////                'OrderItem' => [
////                    'fields' => [
////                        'id'
////                    ]
////                ],
////                'OrderStatus'
//            ]
        ];

        $orderList = $this->Order->find('all',$orderSettings);

        $orderStatistic = [
            'total' => 0,
            'orders' => 0,
            'amount' => 0,
            'used_poins' => 0,
            'statuses' => array_map(function($v){ return 0; }, $orderStatus)
        ];

        $userStatistic = [
            'total' => 0,
            'orders' => 0,
            'amount' => 0,
            'userPoints' => 0
        ];

        $userStatistic['total'] = count($list);

        foreach($list as $user){
            $userStatistic['orders'] += count($user['Order']);
            foreach($user['Order'] as $order){
                $userStatistic['amount'] += $order['amount'];
                $userStatistic['userPoints'] += $order['used_poins'];
            }
        }

        $orderStatistic['total'] = count($orderList);
//
//        foreach($orderList as $order){
//            $orderStatistic['orders'] += count($order['OrderItem']);
//            $orderStatistic['amount'] += $order['Order']['amount'];
////            $orderStatistic['used_poins'] += $order['Order']['used_poins'];
//            $orderStatistic['statuses'][$order['OrderStatus']['status']]++;
//        }

        $this->set([
            'userStatistic' => $userStatistic,
            'orderStatistic' => $orderStatistic,
            'filterPeriods' => $this->filterPeriods,
            'period' => $period
        ]);
    }

    public function index()
    {
        $this->set('list', $this->AdminPage->find('all'));
    }

    public function add()
    {
        $this->setActiveMenu(array('pages', 'addPage'));
        if ($this->request->data) {
            if ($this->AdminPage->save($this->request->data)) {
                $this->setFlash(array('title' => 'Page', 'msg' => 'Page is created'), 'success');
                $this->redirect(array('action' => 'index'));
            } else {
                $this->setFlash('Page is not created', 'error');
            }
        }
        $this->set('title', 'Add page');

    }

    public function edit($id)
    {
        $this->setActiveMenu(array('content'));
        if ($this->request->data) {
            //$key = $this->request->data['AdminPage']['key'];

            $this->AdminPage->id = $id;
            if ($this->AdminPage->save($this->request->data)) {
                $this->setFlash('Page is saved', 'success');
            } else {
                $this->setFlash('Page is not saved', 'error');
            }
        }
        $data = $this->AdminPage->read('', $id);

        $this->request->data = $data;
        $item = $this->Page->find('first',['conditions'=> ['id'=> $id]]);
        $this->set(['page' => $item]);
        $this->render('add');

    }

    public function delete($id)
    {
        $page = $this->AdminPage->findById($id);

        if ($page['AdminPage']['undeleted']){
            $this->setFlash('You can not remove this page', 'error');
        }else{
            if ($this->AdminPage->delete($id)) {
                $this->setFlash('Page is deleted', 'success');
            } else {
                $this->setFlash('Page is not deleted', 'error');
            }
        }

        $this->redirect(array('action' => 'index'));
    }
}
