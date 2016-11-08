<?php

App::uses('AppController', 'Controller');

define('PRODUCT_MAIN_PAGINATE', 6);

/**
 * Products Controller
 *
 * @property Slider $Slider
 * @property Category $Category
 * @property Product $Product
 * @property Order $Order
 * @property User $User
 * @property ShippingComponent $Shipping
 * @property Accessory $Accessory
 *
 */
class ProductsController extends AppController
{

    public $uses = [
        'Product',
        'Category',
        'Order',
        'Discount',
        'User',
        'Accessory',
        'AuthorizeNetComponent',
        'Option',
        'Calcul'
    ];

    public $stripeStatuses = [
        'succeeded' => 4,
        'pending' => 4,
        'failed' => 2
    ];

    public $components = array(
        'RequestHandler',
        'ArrayWalk',
        'Paginator',
        'Shipping',
    );
    public $helpers = array('Js' => array('Jquery'), 'Html', 'Form', 'Paginator');
    public $paginate = array(
        'limit' => PRODUCT_MAIN_PAGINATE,
        'order' => array(
            'Product.position' => 'asc'
        )
    );

    public $validate = array(
        'rental[full_name]' => array(
            // or: array('ruleName', 'param1', 'param2' ...)
            'rule' => 'ruleName',
            'required' => true,
//            'allowEmpty' => false,
            // or: 'update'
            'on' => 'create',
            'message' => 'Your Error Message'
        )
    );

    public function beforeFilter()
    {
        parent::beforeFilter();
    }

    /**
     *  data processing in shop cart - get-started/order
     */

    public function details()
    {
        session_destroy();
        if ($this->request->data){
            $price = $_SESSION['price'];
            session_start();

            if($this->Calcul->details($this->request->data)){
                $data = $this->Calcul->details($this->request->data);
                $data['item_price'] = $price;
                $_SESSION['order'] = $data;
                $this->redirect('/get-started/order');
            }
        }

        $product    = $this->Product->getProduct($this->request->id);
        $accessory  = $this->Accessory->getAll();

        $this->set([
           'product' => isset($product['Product'])?$product['Product']:'',
           'accessory' => $accessory

        ]);

    }

    public function ajaxDetails()
    {
            $data = $this->request->data;
            $arr_rent = $this->Product->getProductRental($data['product_id']);
            $product = $this->Product->getProduct($data['product_id']);
            $rental = [];
            foreach ($arr_rent as $key => $val){
                $rental[$val['ProductRental']['number']] = $arr_rent[$key];
            }

            $sum['sum'] = $data['num'] * $data['price'];

        //calendar days
            if ($data['cal1'] && $data['cal2']){

                $datetime1 = new DateTime($data['cal1']);
                $datetime2 = new DateTime($data['cal2']);
                    $interval = $datetime1->diff($datetime2);
                    $days = $interval->days;
                $days_ac = $days != 0 ? $days : 1;

                $sum['sum'] *= $days_ac;

                if(!empty($rental)){
//                    var_dump(1);

                    if($days != 0){
                        if($this->Calcul->nearest($rental,$days ) == null){
                            $sum['price'] = $product['Product']['price'];
                        }else{
                            $sum['price'] = round($this->Calcul->nearest($rental,$days ),2);
                        }
                    }else{
                        if($this->Calcul->nearest($rental,$days ) == null){
                            $sum['price'] = $product['Product']['price'];
                        }else{
                            $sum['price'] = round($this->Calcul->nearest($rental,$days ),2);
                        }
                    }
                }else{
                    if($days != 0){
                        $sum['price'] = $product['Product']['price'];
                    }else{
                        $sum['price'] = $product['Product']['price'] /2;

                    }
                }

            $sum['sum'] = $sum['price'] * $days_ac * $data['num'] ;
            }

            //number accessory
            if (!empty($data['accessories'])){
                $arrAccess = [];
                foreach ($data['accessories'] as $accessory) {
                    if ($accessory['number'] != '' ){
                        $arrAccess[] = $accessory['number'] * $accessory['price'] * intval($days_ac) ;
                    }
                }
                $sum['sum'] += array_sum($arrAccess);
            }

            $_SESSION['price'] = $sum['price'];

            $sum['hidden'] = '<input type="hidden" name="summa" value=' . $sum["sum"] . '>';
            $sum['days'] = '<input type="hidden" name="days" value=' . $days_ac . '>';

        echo json_encode($sum);
        exit();
    }

    public function orderProduct()
    {

            if (!empty($_SESSION['order'])) {
                $_SESSION['order']['subtotal'] = $_SESSION['order']['summa'];

                $product = $this->Product->getProduct($_SESSION['order']['id']);
                $_SESSION['payment']['product'] = $product['Product'];
                //set access in array
                if (!isset($this->request->data['del_id'])) {
                    if (isset($_SESSION['order']['access'])) {
                        foreach ($_SESSION['order']['access'] as $acces) {
                            foreach ($acces as $acce) {
                                $_SESSION['accessory'][] = $acce;
                            }
                        }
                    }

                    $days = (isset($_SESSION['order']['days'])) ? $_SESSION['order']['days'] : 1;

                    if (!empty($_SESSION['accessory'])) {
                        $this->Calcul->orderProductAccessories($days);
                    }
                }

                $_SESSION['payment']['sum'] = $_SESSION['order']['subtotal'];

                unset($_SESSION['order']['size']);
                unset($_SESSION['order']['access']);

                if (isset($this->request->data['del_id'])) {
                    unset($_SESSION['accessory'][$this->request->data['del_id']]);
                }
                if (!empty($_SESSION['accessory'])) {
                    $accessory = $_SESSION['accessory'];
                } else {
                    $accessory = [];
                }

                $_SESSION['payment']['from'] = $_SESSION['order']['cal1'];
                $_SESSION['payment']['to'] = $_SESSION['order']['cal2'];
//                var_dump($_SESSION['order']);die;
                $this->set([
                    'infoProduct' => $product,
                    'infoOrder' => $accessory,
                    'info' => $_SESSION['order']
                ]);
            }
    }

    public function ajaxOrderDetailsSecond()
    {

        //the removal of the goods from the cart
        if (isset($this->request->data['clear'])){
            session_destroy();
            echo json_encode(1);
        }
        //when removing the accessory from the cart
        if (isset($this->request->data['del_id'])){
            unset($_SESSION['accessory'][$this->request->data['del_id']]);
        }

        $data = $this->request->data;
        $product = $this->Product->getProduct($data['product_id']);
        $rental = $this->Product->getProductRental($data['product_id']);

        $sum['total_price'] = $data['sum'];
        if ($data['cal1'] && $data['cal2']) {

                $_SESSION['payment']['from'] = $data['cal1'];
                $_SESSION['payment']['to'] = $data['cal2'];

                $datetime1 = new DateTime($data['cal1']);
                $datetime2 = new DateTime($data['cal2']);

                $interval = $datetime1->diff($datetime2);
                $days = $interval->days;

                $days_in = $days != 0 ? $days : 1;
                $sum['total_price'] *= $days_in * $data['num'];
                if(!empty($rental)){
                    if($days != 0){
                        if($this->Calcul->nearest($rental,$days ) == null){
                            $sum['price'] = $product['Product']['price'];
                        }else{
                            $sum['price'] = round($this->Calcul->nearest($rental,$days ),2);
                        }
                    }else{
                        if($this->Calcul->nearest($rental,$days ) == null){
                            $sum['price'] = $product['Product']['price'];
                        }else{
                            $sum['price'] = round($this->Calcul->nearest($rental,$days ),2);
                        }
                    }
                }else{
                    if($days != 0 ){
                        $sum['price'] = $product['Product']['price'];
                    }else{
                        $sum['price'] = $product['Product']['price'] / 2;
                    }
                }


            $sum['total_price'] = $sum['price'] *  $data['num'] * $days_in;

        }else{
            $days = 1;
            $sum['total_price'] *= $data['num'];
        }

//        var_dump($sum['total_price']);die;

        if (isset($_SESSION['accessory'])) {

            if($_SESSION['accessory']){
                $_SESSION['counter'] = max(array_keys($_SESSION['accessory']))+ 2;
            }

            for ($i = 0; $i < $_SESSION['counter']; $i++) {
                if(isset($_SESSION['accessory'][$i])){
                    $sum['for_total_price'][] = $_SESSION['accessory'][$i]['Accessory']['price'] * $days_in;
                }
            }

            if(!empty($sum['for_total_price'])){
                $sum['sub_total'] = array_sum($sum['for_total_price']) + $sum['total_price'];

            }else{
                $sum['sub_total'] = $sum['total_price'];
            }
        }else{
            $sum['sub_total'] = $sum['total_price'];
        }

        if ($data['discount']){

            $discount = $this->Discount->find('first',[
                'conditions' => [
                    'key_word' => $data['discount'],
                    'active' => 1
                ]
            ]);

            if (!empty($discount)){
                $_SESSION['payment']['discount'] = $data['discount'];
                if ($discount['Discount']['type'] == 'percent'){
                    $sum['super_total'] = $sum['sub_total'] - $sum['sub_total']*( $discount['Discount']['value'] / 100);
                    $sum['discount'] = '-' .  $discount['Discount']['value'] . '%';

                }else{
                    if($sum['sub_total'] > $discount['Discount']['value']){
                        $sum['super_total'] = $sum['sub_total'] - $discount['Discount']['value'];
                        $sum['discount'] = '-$' .  $discount['Discount']['value'];
                    }else{
                        $sum['discount'] = '<span style="color: #f60002;">No possible</span>';
                    }

                }
            }else{
                $sum['discount'] = '00.00$';
            }
        }

//        $sum['total_price'] = $sum['total_price']),2);
//        $sum['sub_total'] = $sum['sub_total']);
        $sum['super_total'] = (!empty($sum['super_total']))? $sum['super_total'] : $sum['sub_total'];
        $_SESSION['payment']['sum'] = $sum['super_total'];
//        var_dump($sum['price']);die;
        echo json_encode($sum);

        exit();
    }
    public function ajax_payment()
    {
        $data = $this->request->data;

        $dataAutorize = [
            'user_id'     => '1',
            'fname'       => $data['first_name'],
            'lname'       => $data['last_name'],
            'phone'       => $data['phone'],
            'email'       => $data['email'],
            'address'     => $data['address'],
            'city'        => $data['billingAddressCity'],
            'country'     => $data['country'],
            'zip'         => $data['zip_code'],
            'card_number' => $data['card_number'],
            'exp_date'    => $data['expiration_date'] . $data['year'], // MMYYYY
            'total_price' => $data['order_sum'],
        ];

        $response = $this->AuthorizeNetComponent->chargeCard($dataAutorize);

        if ($response['status'] == '1'){

            $response['success'] = $response['message'];

            $arrVar = [
                'product_name'      => $data['order_product_name'],
                'sum'               => $data['order_sum'],
                'delivery_time'     => $data['order_delivery_time'],
                'full_name'         => $data['full_name'],
                'email'             => $data['email'],
                'phone'             => $data['phone'],
                'address'           => $data['address'],
                'address2'          => $data['address2'],
                'city'              => $data['city'],
                'zip_code'          => $data['zip_code'],
                'bA_address'        => $data['billingAddressAddress'],
                'bA_city'           => $data['billingAddressCity'],
                'state'             => $data['state'],
                'bA_zip_code'       => $data['billingZip_code'],
                'country'           => $data['country'],
                'first_name'        => $data['first_name'], //1
                'last_name'         => $data['last_name'],
                'security_code'     => $data['security_code'],
                'card_number'       => $data['card_number'],
                'expiration_date'   => $data['expiration_date'],
                'year'              => $data['year'],
//                'i_agree'           => $data['billingInfo']['i_agree'],
                'from'              => $_SESSION['payment']['from'],
                'to'                => $_SESSION['payment']['to']
            ];

            if($this->htmlToPdf($arrVar)){

                $order_accessory = [];


                $order = [
                    'name_product'      => $data['order_product_name'],
                    'full_name'         => $data['full_name'],
                    'city'              => $data['city'],
                    'amount'            => $data['order_sum'],
                    'order_status_id'   => 0,
                    'discount_key'      => isset($_SESSION['payment']['discount'])?$_SESSION['payment']['discount']:'',
                    'file'              => isset($_SESSION['file_pdf'])? $_SESSION['file_pdf']:'',
                    'status'            => 'New',
//                    'payment_status'    => $response['message']
                ];
                $this->Order->save($order);


                $billingInfo = [
                    'order_id'          => $this->Order->getLastInsertID(),
                    'first_name'        => $data['first_name'],
                    'last_name'         => $data['last_name'],
                    'address_1'         => $data['address'],
                    'address_2'         => $data['address2'],
                    'billing_address'   => $data['billingAddressAddress'],
                    'billing_zip'       => $data['billingZip_code'],
                    'zip'               => $data['zip_code'],
                    'country'           => $data['country'],
                    'state'             => $data['state'],
                    'city'              => $data['billingAddressCity'],
                    'phone'             => $data['phone'],
                    'email'             => $data['email'],
                    'delivery_time'     => $data['delivery_time'],
                    'from_date'         => $_SESSION['payment']['from'],
                    'to_date'           => $_SESSION['payment']['to']
                ];

                $i = 0;

                foreach ($_SESSION['accessory'] as $item) {
                    $order_accessory[$i]['accessory_id'] = $item['Accessory']['id'];
                    $order_accessory[$i]['size'] = $item['Accessory']['size'];
                    $order_accessory[$i]['order_id'] = $this->Order->getLastInsertID();

                    $i++;
                }
                $_SESSION['accessory'] = [];

                $this->loadModel('OrderBillingInfo');
                $this->OrderBillingInfo->save($billingInfo);
                $this->loadModel('OrderAccessory');
                $this->OrderAccessory->saveAll($order_accessory);

                $arr = [
                    'name_pdf' => $_SESSION['file_pdf'],
                    'dataVar' => $arrVar
                ];

                $this->sendEmail($arr);

                $this->flashMsg('Order is processed', 'info');

                echo json_encode($response);

            }

        }else{

//          $this->flashMsg($response['message'], 'warning');
            $response['error'] = $response['message'];
            echo json_encode($response);
//          $this->redirect('/get-started/order/payment');

        }
        exit();
    }

    public function payment($data = null)
    {
        if (!empty($data)){
            $arrVar = [
                'product_name'      => $data['order_product_name'],
                'sum'               => $data['order_sum'],
                'delivery_time'     => $data['order_delivery_time'],
                'full_name'         => $data['full_name'],
                'email'             => $data['email'],
                'phone'             => $data['phone'],
                'address'           => $data['address'],
                'address2'          => $data['address2'],
                'city'              => $data['city'],
                'zip_code'          => $data['zip_code'],
                'bA_address'        => $data['billingAddressAddress'],
                'bA_city'           => $data['billingAddressCity'],
                'state'             => $data['state'],
                'bA_zip_code'       => $data['billingZip_code'],
                'country'           => $data['country'],
                'first_name'        => $data['first_name'], //1
                'last_name'         => $data['last_name'],
                'security_code'     => $data['security_code'],
                'card_number'       => $data['card_number'],
                'expiration_date'   => $data['expiration_date'],
                'year'              => $data['year'],
//                'i_agree'           => $data['billingInfo']['i_agree'],
                'from'              => $_SESSION['payment']['from'],
                'to'                => $_SESSION['payment']['to']
            ];

            if($this->htmlToPdf($arrVar)){

                $order_accessory = [];


                $order = [
                    'name_product'      => $data['order_product_name'],
                    'amount'            => $data['order_sum'],
                    'order_status_id'   => 0,
                    'discount_key'      => isset($_SESSION['payment']['discount'])?$_SESSION['payment']['discount']:'',
                    'file'              => isset($_SESSION['file_pdf'])? $_SESSION['file_pdf']:'',
                    'status'            => 'New',
//                    'payment_status'    => $response['message']
                ];

                $this->Order->save($order);


                $billingInfo = [
                    'order_id'     => $this->Order->getLastInsertID(),
                    'first_name'   => $data['first_name'],
                    'last_name'    => $data['last_name'],
                    'address_1'    => $data['address'],
                    'address_2'    => $data['address2'],
                    'zip'          => $data['zip_code'],
                    'country'      => $data['country'],
                    'state'        => $data['state'],
                    'city'         => $data['billingAddressCity'],
                    'phone'        => $data['phone'],
                    'email'        => $data['email'],
                    'dilivery_time'=> $data['delivery_time']

                ];

                $i = 0;

                foreach ($_SESSION['accessory'] as $item) {
                    $order_accessory[$i]['accessory_id'] = $item['Accessory']['id'];
                    $order_accessory[$i]['size'] = $item['Accessory']['size'];
                    $order_accessory[$i]['order_id'] = $this->Order->getLastInsertID();

                    $i++;
                }

                $this->loadModel('OrderBillingInfo');
                $this->loadModel('OrderAccessory');

                $this->OrderBillingInfo->save($billingInfo);
                $this->OrderAccessory->saveAll($order_accessory);

                $arr = [
                    'name_pdf' => $_SESSION['file_pdf'],
                    'dataVar' => $arrVar
                ];

                $this->sendEmail($arr);

                $this->flashMsg('Order is processed', 'info');
//                $this->redirect('/get-started');
            return true;
            }
        }

        $this->set([
            'info' => $_SESSION['payment']
        ]);
    }

    protected function htmlToPdf($data)
    {
        App::import('Vendor', 'mPDF', array('file' => 'mpdf' . DS . 'mpdf.php'));

        $mpdf = new mPDF('utf-8', 'A4', 0, '', 5, 5, 5, 5);
        $this->set($data);
        $html = $this->render('../Emails/html/main')->body();
//        $html = substr($html, 5310, -49000);
        $mpdf->WriteHTML($html);

        $namePdf = time();
        $_SESSION['file_pdf'] = "filesPDF/$namePdf.pdf";
        $mpdf->Output(( "filesPDF/$namePdf.pdf"), 'F');

        return true;
    }

    protected function sendEmail($data)
    {

        App::uses('CakeEmail', 'Network/Email');
        $email = $this->Option->find('first', [
            'conditions' => [
                'Option.key' => 'email',
                'Option.group' => 'contacts'
            ]
        ]);

        try {
            $this->Email = new CakeEmail();
            $this->Email->subject('Order products '. $data['dataVar']['product_name']  )
                        ->attachments($data['name_pdf'])
                        ->to($email['Option']['value'])
                        ->from('info@' . $_SERVER['SERVER_NAME']);
            $this->Email->send();

        } catch (Exception $e) {
//            die('Sorry, server not found. Details:' . $e);
            return false;
        }
        return true;
    }

    public function rentalFee()
    {
        $data = $this->request->data;
        $_SESSION['payment']['from'] = $data['from'];
        $_SESSION['payment']['to'] = $data['to'];
        $_SESSION['payment']['sum'] = round($data['price'], 2);
        $_SESSION['payment']['product'] = '';
        echo json_encode(1);

        exit();
    }


    public function index($cKey = null)
    {
        $category = $this->Category->findByKey($cKey);

        if (empty($category)) {
            $category = $this->Category->find('first');
        }

        $this->Paginator->settings = $this->paginate;
        $products = $this->Paginator->paginate('Product', [
            'Product.active' => true,
            'Product.category_id' => !empty($category) ? $category['Category']['id'] : null
        ]);

        $this->set([
            'category' => $category,
            'products' => $products
        ]);
        if ($this->request->is('ajax')) {
            $this->render('ajax', 'ajax'); // View, Layout
        }
    }

    public function sales()
    {
        $this->loadModel('Static');

        $this->set([
            'sales' => $this->Static->findById('sales-text')
        ]);

        $settings = [
            'conditions' => [
                'Product.on_sale' => 1
            ]
        ];

        $list = $this->Product->find('all', $settings);

        $this->set('products', $list);
    }

    public function collection($cKey = null)
    {
        $category = $this->Category->findByKey($cKey);

        if (empty($category)) {
            $category = $this->Category->find('first');
        }

        $this->setLastCollectionPage($category['Category']['key']);

        $this->Paginator->settings = $this->paginate;

        $products = $this->Paginator->paginate('Product', [
            'Product.active' => true,
            'Product.category_id' => !empty($category) ? $category['Category']['id'] : null
        ]);

        $this->set([
            'category' => $category,
            'products' => $products
        ]);
        if ($this->request->is('ajax')) {
            $this->render('ajax', 'ajax'); // View, Layout
        }else{
            $this->render('index');
        }
    }

    public function viewByUrl($url)
    {
        $item = $this->Product->getProductByUrl('/'.$url);
        $this->view($item);
        $this->render('view');
    }

    public function view($idOrItem)
    {
        if (is_array($idOrItem)) {
            $item = $idOrItem;
        } else {
            $item = $this->Product->getProduct($idOrItem);
        }

        $data = [
            'product' => $item,
            'inCart' => $this->Cart->checkItem($item['Product']['id'])
        ];
        if (isset($item['Product']['meta_keywords'])) {
            $data['metaKey'] = $item['Product']['meta_keywords'];
        }
        if (isset($item['Product']['meta_title'])) {
            $data['metaTitle'] = $item['Product']['meta_title'];
        }
        if (isset($item['Product']['meta_description'])) {
            $data['metaKey'] = $item['Product']['meta_description'];
        }

        $this->set($data);

    }

    public function addToCart($pId)
    {
        $redirectLink = '/products/cart';
        $product = $this->Product->find('first', [
            'conditions' => [
                'Product.id' => $pId
            ]
        ]);
        if ($product) {
            $data = [
                'product_id' => $pId,
                'quantity' => 1
            ];

            if ($this->Cart->addItem($data)) {
                $this->flashMsg('Product was added to cart', 'info');
            }

            if ($product['Product']['url']) {
                $redirectLink = '/product/'.$product['Product']['url'];
            } else {
                $redirectLink = '/products/view/'.$product['Product']['id'];
            }
        }

        $this->redirect($redirectLink);
    }

    public function cart()
    {
        $this->Cart->applyShipping(0, null);
        $this->set([
            'cart' => $this->cartRecount(),
            'lastPage' => $this->getLastCollectionPage(),
            'shoppingCart' => true
        ]);
    }

    public function deleteFromCart($id = null)
    {
        if ($id && $this->Cart->removeItem($id)) {
            $this->flashMsg('Product was deleted from cart', 'info');
        }

        $this->redirect('/products/cart');
    }

    public function changeQuantity()
    {
        if ($this->request->is('ajax')) {
            $data = $this->request->query;
            $newQuantity = $this->Cart->changeItemQuantity($data['id'], $data['d']);
            $this->GoodAjax->ajaxResponse['newQuantity'] = $newQuantity;

            $cart = $this->cartRecount();
            unset($cart['items']);

            $this->GoodAjax->ajaxResponse['cart'] = $cart;
            $this->GoodAjax->sendAjax();
        }
    }

    public function checkout()
    {
        $this->Cart->applyShipping(0, null);
        $cart = $this->cartRecount();

        if(!$cart['items']){
            $this->flashMsg('No data', 'warning');
            $this->redirect('/products/cart');
        }

        if ($this->request->data) {
            $order = $this->request->data;

            if ($this->Session->check('orderInfo')) {
                $order = array_merge($this->Session->read('orderInfo'), $order);
            }

            $order['OrderItem'] = $cart['items'];
            $order['Order'] = [
                'user_id' => $this->userId,
                'amount' => $cart['total'],
                'discount_key' => $cart['coupon']
            ];

            if ($this->Order->saveAll($order)) {
                $this->Session->write('orderInfo', $this->request->data);
                $this->Session->write('orderId', $this->Order->getLastInsertId());
                $this->redirect('/products/payments');
            }
        }

        if (!$this->Session->check('orderInfo')) {
            if ($this->userId) {
                $user = $this->User->getUserBy($this->userId, array('UserInfo','UserShippingInfo','UserBillingInfo'));
                $this->request->data['OrderBillingInfo'] = $user['UserBillingInfo'];
                $this->request->data['OrderShippingInfo'] = $user['UserShippingInfo'];
                $this->set('userInfo', true);
            }
        } else {
            $this->request->data = $this->Session->read('orderInfo');
        }

        $this->loadModel('State');
        $this->set([
            'states' => $this->State->find('list', ['fields' => ['name', 'name'], 'order' => ['name' => 'asc']]),
            'shippingBilling' => true
        ]);
    }

    public function payments()
    {
        if(!$this->Session->check('orderId')) {
            $this->flashMsg('First complete, please billing data', 'warning');
            $this->redirect('/products/checkout');
        }

        $settings = [
            'conditions' => [
                'Order.id' => $this->Session->read('orderId')
            ],
            'contain' => [
                'OrderBillingInfo',
                'OrderShippingInfo',
                'OrderItem' => [
                    'Product' => [
                        'fields' => ['title', 'price', 'width', 'height', 'length', 'weight']
                    ]
                ]
            ]
        ];

        $order = $this->Order->find('first', $settings);
        $user = $this->userId ? $this->User->getUserBy($this->userId) : null;
        $potgCoefficient = ClassRegistry::init('Option')->getByKey('potg_coefficient');
        $ptodCoefficient = ClassRegistry::init('Option')->getByKey('ptod_coefficient');

        $shippingInfo = $this->Shipping->calculateShippingRate($order);

        if (is_string($shippingInfo)){
            $this->flashMsg($shippingInfo, 'Error');
            $this->redirect('/products/checkout');
        }

        //->RatedShipment[0]->TotalCharges->MonetaryValue
        //->RatedShipment[0]->TotalCharges->CurrencyCode

        if (!$order){
            $this->flashMsg('Some wrong, please complete cart');
            $this->redirect('/products/cart');
        }

        $rewardPoints = round($order['Order']['amount'] * $potgCoefficient);
//        $shippingTypes = ClassRegistry::init('Shipping')->find('list', ['fields' => ['title', 'value']]);

        $this->set([
            'order' => $order,
            'user' => $user,
            'payments' => true,
            'ptodCoefficient' => $ptodCoefficient,
            'rewardPoints' => $rewardPoints,
            'shippingInfo' => $shippingInfo,
//            'shippingTypes' => $shippingTypes
        ]);
    }

//    public function test() {
//        $this->Order->contain([
//            'OrderItem.Product',
//            'OrderStatus',
//            'User',
//            'OrderBillingInfo',
//            'OrderShippingInfo'
//        ]);
//
//
//        $order = $this->Order->findById(18);
//
//        $this->Messenger->testmail($order['OrderBillingInfo']['email'], $order, $this->_options);
//
//
//    }

    public function pay()
    {
        if ($this->request->is('ajax')) {

            $data = $this->request->data['Payment'];

            if (!$this->Session->check('orderId') || !$data) {
                $this->GoodAjax->ajaxResponse['error'] = true;
                $this->GoodAjax->ajaxResponse['msg'] = 'Some wrong';
            } else {

                $this->Order->contain([
                    'OrderItem.Product',
                    'OrderStatus',
                    'User',
                    'OrderBillingInfo',
                    'OrderShippingInfo'
                ]);

                $order = $this->Order->findById($this->Session->read('orderId'));
                $user = $this->userId ? $this->User->getUserBy($this->userId) : null;

                $amount = $this->calculateCartSumm($order, $user, $data);

                $respond = $this->stripPaymentQuery($amount, $data);


                $this->Order->save([
                    'id' => $order['Order']['id'],
                    'user' => $user,
                    'order_status_id' => $respond['status'],
                    'used_poins' => $amount['used'],
                    'shipping' => $amount['shipping'],
                    'shipping_service' => $amount['shipping_service'],
                ]);

                if ($respond['status'] == 0){
                    $this->GoodAjax->ajaxResponse['error'] = true;
                    $this->GoodAjax->ajaxResponse['msg'] = $respond['msg'];
                }else{
                    $potgCoefficient = ClassRegistry::init('Option')->getByKey('potg_coefficient');
                    if ($this->userId) {
                        $this->User->updateUserPointsBalance($amount, $this->userId, $potgCoefficient);
                    }

                    $this->Session->write('feedbackOrderId', $this->Session->read('orderId'));

                    $this->Cart->removeCart();
                    $this->Session->delete('orderInfo');
                    $this->Session->delete('orderId');

                    $order = array_merge($order, $amount);

                    $this->Messenger->youvepay($order['OrderBillingInfo']['email'], $order, $this->_options);

                    $to = ClassRegistry::init('Option')->findByKey('notifications_email');
                    if (!empty($to['Option']['value'])) {
                        $this->Messenger->payment($to['Option']['value'], $order, $this->_options);
                    }

                    $this->GoodAjax->ajaxResponse['success'] = true;
                }
            }
        }

        $this->GoodAjax->sendAjax();
    }

    private function stripPaymentQuery(array $amount, array $data)
    {
        $responds = [
            'error' => false,
            'msg' => null,
            'status' => 0
        ];

        Configure::load('stripeKeys');
        $keys = Configure::read('stripeKeys');

        App::import('Vendor', 'stripe', array('file' => 'stripe-php-3.12.1' . DS . 'init.php'));

        \Stripe\Stripe::setApiKey($keys['live']['secret']);

        try {
            $token = \Stripe\Token::create(array(
                "card" => array(
                    'name' => $data['account_holder'],
                    "number" => $data['account_number'],
                    "exp_month" => $data['expiration_month'],
                    "exp_year" => $data['expiration_year'],
                    "cvc" => $data['security_code']
                )
            ));


            try {
                $amountToCents = $amount['amount'] * 100;

                $charge = \Stripe\Charge::create(array(
                    "amount" => $amountToCents,
                    "currency" => "usd",
                    "source" => $token->id,
                    "description" => "Payment for cart #{$this->Session->read('orderId')}"
                ));

                $responses = $charge->getLastResponse()->json;

                $responds['status'] = $this->stripeStatuses[$responses['status']];

            } catch (Exception $e) {
                $responds['error'] = true;
                $responds['msg'] = $e->getMessage();
            }

        } catch (Exception $e) {
            $responds['error'] = true;
            $responds['msg'] = $e->getMessage();
        }

        return $responds;
    }

    private function calculateCartSumm($order, $user, $formData)
    {
        $responds = [
            'amount' => 0,
            'used' => 0
        ];

        $useDollars = empty($formData['potg']) ? 0 : ((int)$formData['potg'] > 0 ? (int)$formData['potg'] : 0);
        $potgCoefficient = ClassRegistry::init('Option')->getByKey('ptod_coefficient');

        $usePoints = $useDollars / $potgCoefficient;

        $cart = $this->cartRecount();
        $responds['amount'] = $cart['total'];
        $responds['shipping'] = $cart['shipping'];
        $responds['shipping_service'] = $cart['shipping_service'];

        if ($user['User']['point_balance'] >= $usePoints){
            $responds['amount'] -= $useDollars;
            $responds['amount'] = $responds['amount'] < 0 ? 0 : $responds['amount'];
            $responds['used'] = $usePoints;
        }

        return $responds;
    }

    public function success()
    {
        $this->Cart->removeCart();
        $this->loadModel('Static');
        $this->set([
            'success' => $this->Static->findById('products-success')
        ]);
    }

    public function usePoints()
    {
        $q = isset($this->request->query['q']) ? (int)$this->request->query['q'] : 0;

        $cart = $this->cartRecount();
        $user = $this->User->getUserBy($this->userId);
        unset($cart['items']);

        $potgCoefficient = ClassRegistry::init('Option')->getByKey('ptod_coefficient');
        $canUse = $user['User']['point_balance'] * $potgCoefficient;

        if ($q > 0 && $user){
            if ($canUse < $q) {
                $this->GoodAjax->ajaxResponse['error'] = true;
                $this->GoodAjax->ajaxResponse['msg'] = "You can use max {$canUse} POTG Rewards Dollars";
            }else{
                $cart['total'] -= $q;
                $cart['total'] = $cart['total'] < 0 ? 0 : $cart['total'];
            }
        }

        $this->GoodAjax->ajaxResponse['cart'] = $cart;
        $this->GoodAjax->sendAjax();
    }

    public function shippingUpdate()
    {
        $q = $this->request->query('q') ?: 0;
        $shipping = $this->request->query('shipping') ?: 0;
        $service = $this->request->query('service') ?: 0;

        $settings = [
            'conditions' => [
                'Order.id' => $this->Session->read('orderId')
            ],
            'contain' =>[
                'OrderBillingInfo',
                'OrderShippingInfo',
                'OrderItem' => [
                    'Product' => [
                        'fields' => ['title', 'price', 'width', 'height', 'length', 'weight']
                    ]
                ]
            ]
        ];

        $order = $this->Order->find('first', $settings);
        $user = $this->userId ? $this->User->getUserBy($this->userId) : null;

        $shippingInfo = $this->Shipping->calculateShippingRate($order, $service);

        if (!$shippingInfo) {
            $this->GoodAjax->ajaxResponse['error'] = true;
            $this->GoodAjax->ajaxResponse['msg'] = "Shipping service fail";
        }

        $shipping_cost = $shippingInfo->RatedShipment[0]->TotalCharges->MonetaryValue;
        $this->Cart->applyShipping($shipping_cost, $service);

        $cart = $this->cartRecount();
        $user = $this->User->getUserBy($this->userId);
        unset($cart['items']);

        $potgCoefficient = ClassRegistry::init('Option')->getByKey('ptod_coefficient');
        $canUse = $user ? $user['User']['point_balance'] * $potgCoefficient : 0;

        if ($q > 0 && $user){
            if ($canUse < $q) {
                $q = $canUse;
            }
            $cart['total'] -= $q;
            $cart['total'] = $cart['total'] < 0 ? 0 : $cart['total'];
        }

        $this->GoodAjax->ajaxResponse['cart'] = $cart;
        $this->GoodAjax->sendAjax();
    }

    public function applyDiscountCode()
    {
        if ($this->request->is('ajax')) {
            $coupon = $this->request->data['Coupon'];

            $settings = [
                'conditions' => [
                    'Discount.key_word' => $coupon['code'],
                    'AND' => [
                        'Discount.from_date <=' => date('Y-m-d', time()),
                        'Discount.to_date >=' => date('Y-m-d', time())
                    ],
                    'Discount.active' => true,
                ]
            ];

            $discount = $this->Discount->find('first', $settings);

            if($discount){
                $this->Cart->applayDiscount($coupon['code']);
                $cart = $this->cartRecount();
                unset($cart['items']);
                $this->GoodAjax->ajaxResponse['cart'] = $cart;
                $this->GoodAjax->ajaxResponse['msg'] = 'The code is valid.';
            }else{
                $this->GoodAjax->ajaxResponse['error'] = true;
                $this->GoodAjax->ajaxResponse['msg'] = 'The code is Invalid. Try again.';
            }
        }

        $this->GoodAjax->sendAjax();
    }

    public function addComment()
    {
        if ($this->request->is('ajax')) {
            if ($this->Product->ProductComments->save($this->request->data)) {
                $this->GoodAjax->ajaxResponse['msg'] = 'Thanks for your comment';
                $this->set('comment', $this->request->data['ProductComments']);
                $this->GoodAjax->ajaxResponse['content'] = '../Elements/itemOfProductComment';
            } else {
                $this->GoodAjax->ajaxResponse['errorDesc'] = $this->formatErrors($this->Product->ProductComments->validationErrors);
            }

            $this->GoodAjax->sendAjax();
        }
    }

    private function cartRecount()
    {
        $cart = $this->Cart->getCart();
        $cart['subtotal'] = 0;

        foreach($cart['items'] as $key=>$value){
            $item = $this->Product->getProduct($value['product_id']);
            $cart['items'][$key]['product'] = $item;
            $cart['subtotal'] += $item['Product']['price'] * $value['quantity'];
        }
        $cart['total'] = $cart['subtotal'];

        $discount = $this->Discount->findByKeyWord($cart['coupon']);
        if ($discount){
            switch($discount['Discount']['type'])
            {
                case 'flat-rate':
                    $total = round(($cart['subtotal'] - $discount['Discount']['value']),2);
                    $cart['total'] = $total < 0 ? 0 : $total;
                    break;
                case 'percent':
                        $percent = ($discount['Discount']['value'] > 100 || $discount['Discount']['value'] < 0) ? 0 : $discount['Discount']['value'];
                    $cart['total'] = round(($cart['subtotal'] - ($cart['subtotal'] * $percent / 100)),2);
                    break;
            }
        }

        $cart['shipping'] = isset($cart['shipping']) ? $cart['shipping'] : 0;
        if ($cart['shipping']) {
            $cart['total'] += $cart['shipping'];
        }

        return $cart;
    }

    public function re($id = null)
    {
        if ($this->request->is('ajax')) {
            $result = $this->Cart->reAddToCart($id, $this->userId);
            if ($result === true) {
                $this->GoodAjax->ajaxResponse['error'] = false;
                $this->GoodAjax->ajaxResponse['message'] = 'OK';
            } else {
                $this->GoodAjax->ajaxResponse['error'] = true;
                $this->GoodAjax->ajaxResponse['errorDesc'] = $result;
            }
            $this->GoodAjax->sendAjax();
        } else {
            $this->redirect('/');
        }
    }

    public function cancel()
    {
        if ($this->request->is('ajax') && $this->userId) {
            $result = 'You cant cancel this order';

            $orderId = $this->request->data['id']?:null;
            $reason = strip_tags($this->request->data['cancel_reason']?:'');
            $order = $this->Order->find('first', [
                'conditions' => [
                    'Order.id' => $orderId,
                    'Order.user_id' => $this->userId,
                    'OrderStatus.cancellable' => 1
                ],
                'contain' => [
                    'OrderStatus'
                ]
            ]);
            if ($order) {
                $order = [
                    'id' => $orderId,
                    'order_status_id' => 3,
                    'cancel_reason' => $reason
                ];
                $this->Order->save($order);
                $result = true;
            }
            if ($result === true) {
                $this->GoodAjax->ajaxResponse['error'] = false;
                $this->GoodAjax->ajaxResponse['message'] = 'OK';
            } else {
                $this->GoodAjax->ajaxResponse['error'] = true;
                $this->GoodAjax->ajaxResponse['errorDesc'] = $result;
            }
            $this->GoodAjax->sendAjax();
        } else {
            $this->redirect('/');
        }
    }

    protected function setLastCollectionPage($key)
    {
        $this->Session->write('last_collection_page', $key);
    }

    protected function getLastCollectionPage()
    {
        if ($this->Session->check('last_collection_page')) {
            return '/products/collection/' . $this->Session->read('last_collection_page');
        }
        return '/products/collection';
    }
}