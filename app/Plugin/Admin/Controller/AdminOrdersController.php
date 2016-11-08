<?php
App::uses('AdminAppController', 'Admin.Controller');
/**
 *@property Order $Order
 */
class AdminOrdersController extends AdminAppController
{
//    const ACTIVE = 1;
//    const INACTIVE = 0;

    public $uses = ['Accessory','OrderBillingInfo'];

    public function beforeFilter() {
        parent::beforeFilter();
        $this->setHoverFlag('orders');
        $this->setActiveMenu(array('orders'));
    }

    public function index()
    {
        $order = $this->OrderBillingInfo->getAll();
        $orders = array_reverse($order);
//    var_dump($orders);die;
        $this->set(compact('orders'));
    }

    public function newOrders() {
        $query = $this->request->query;

        $this->Order->virtualFields['fullName'] = 'CONCAT(UserInfo.first_name, " ", UserInfo.last_name)';
        $this->Order->virtualFields['days'] = 'DATEDIFF(Order.created, CURRENT_TIMESTAMP())';
        $conditions = ['or' => []];

        if(isset($query['search'])) {
            $conditions['or'] = [
                'Order.fullName like    ' => '%' . $query['search'] . '%',
                'OrderStatus.status like' => '%' . $query['search'] . '%',
            ];
        }

        $conditions['and'] = [
            'Order.days >' => '-7',
            'Order.days <' => '0'
        ];

        $settings  = [
            'limit' => isset($query['count'])?$query['count']:10,
            'order' => ['Order.id' => 'desc'],
            'fields' => [
                'User.*', 'Order.*', 'UserInfo.*', 'OrderStatus.*'
            ],
            'conditions' => $conditions,
            'joins' => [
                [
                    'table' => 'order_statuses',
                    'alias' => 'OrderStatus',
                    'type' => 'left',
                    'conditions' => [
                        'Order.order_status_id = OrderStatus.id'
                    ]
                ],
                [
                    'table' => 'users',
                    'alias' => 'User',
                    'type' => 'left',
                    'conditions' => [
                        'Order.user_id = User.id'
                    ]
                ],
                [
                    'table' => 'user_infos',
                    'alias' => 'UserInfo',
                    'type' => 'left',
                    'conditions' => [
                        'User.id = UserInfo.user_id'
                    ]
                ]
            ],
            'contain' =>[
                'OrderItem',
            ]
        ];

        $this->Paginator->settings = $settings;

        $list = $this->Paginator->paginate('Order');
        $this->set('list', $list);
        $this->set('query', $query);
        $this->render('index');
    }


    public function edit($id)
    {
        $this->setActiveMenu(array('orders'));

        if($this->request->data){
            $this->loadModel('OrderBillingInfo');
            $this->loadModel('OrderAccessory');

            if($this->Order->save($this->request->data['Order'])){
                if ($this->OrderBillingInfo->save($this->request->data['OrderBillingInfo'])){
                    $this->setFlash('Order is deleted', 'success');
                }else{
                    $this->setFlash('Order is not deleted', 'error');
                }
            }else{
                $this->setFlash('Order is not deleted', 'error');

            }
            if (isset($this->request->data['OrderAccessory'])){
                $this->OrderAccessory->saveAll($this->request->data['OrderAccessory']);
            }
        }

        $order = $this->Order->find('all',['conditions'=>[
            'Order.id' => $id
        ]]);

        $access = $this->Accessory->getOrderAccess($id);

        if(isset($access[0]['OrderAccessory']['id'])){
           $accessory = [];
            foreach ($access as $acces) {
                if($acces['OrderAccessory']['id']){
                    $accessory[] = $acces;
                }
            }
        }else{
            $accessory = '';
        }

        $this->set([
            'order' => $order[0],
            'access' => $accessory,

        ]);
    }

    public function delete($id) {

        if ($this->Order->delete($id)) {
            $this->setFlash('Order is deleted', 'success');
        } else {
            $this->setFlash('Order is not deleted', 'error');
        }

        $this->redirect($this->referer());
    }

}