<?php
App::uses('Component', 'Controller');

/**
 * Created by PhpStorm.
 * User: eugene
 * Date: 03.02.16
 * Time: 14:50
 */

class CartComponent extends Component
{
    private $controller = null;

    protected $name = 'Cart';

    protected $couponValue = 0;

    protected $cartBody = [
        'coupon' => null,
        'items' => []
    ];

    public $couponTypes = [
        'percent',
        'dollars'
    ];

    public $components = ['Session'];

    public function initialize(Controller $controller, $settings = array())
    {
        $this->controller =& $controller;
    }

    public function generateCart()
    {
        if (!$this->Session->check($this->name)){
            return $this->Session->write($this->name, $this->cartBody);
        }
    }

    public function changeItemQuantity($pId, $direction)
    {
        $cart = $this->getCart();

        foreach($cart['items'] as $key=>$item){
            if ($item['product_id'] == $pId){
                $newQuantity = $direction == 'plus' ? ++$item['quantity'] : --$item['quantity'];
                $newQuantity = $newQuantity < 1 ? 1 : $newQuantity;
                $cart['items'][$key]['quantity'] = $newQuantity;

                break;
            }
        }

        if (isset($newQuantity)){
            $this->updateCart($cart);
            return $newQuantity;
        }

        return false;
    }

    public function getItems()
    {
        $cart = $this->getCart();
        return $cart['items'];
    }

    public function getCart()
    {
        $this->generateCart();
        return $this->Session->read($this->name);
    }

    public function removeItem($pId)
    {
        $cart = $this->getCart();

        foreach($cart['items'] as $key=>$item){
            if ($item['product_id'] == $pId){
                unset($cart['items'][$key]);
                break;
            }
        }

        return $this->updateCart($cart);
    }

    public function checkItem($pId)
    {
        $cart = $this->getItems();

        if($cart){
            foreach($cart as $key=>$item){
                if ($item['product_id'] == $pId){
                    return true;
                }
            }
        }

        return false;
    }

    public function addItem(array $item)  // ['product_id' => int, 'quantity' => int, 'properties' => 'json']
    {
        $cart = $this->getCart();
        if ($this->checkItem($item['product_id'])) {
            while ($item['quantity']-- > 0) {
                $this->changeItemQuantity($item['product_id'], 'plus');
            }
            $cart = $this->getCart();
        } else {
            array_push($cart['items'], $item);
        }

        return $this->updateCart($cart);
    }

    public function removeCart()
    {
        return $this->Session->write($this->name, $this->cartBody);
    }

    private function updateCart($cart)
    {
        $this->removeCart();
        return $this->Session->write($this->name, $cart);
    }

    public function applayDiscount($code)
    {
        $cart = $this->getCart();
        $cart['coupon'] = $code;
        return $this->updateCart($cart);
    }

    public function applyShipping($cost, $service)
    {
        $cart = $this->getCart();
        $cart['shipping_service'] = $service;
        $cart['shipping'] = $cost;
        return $this->updateCart($cart);
    }

    /**
     * @param $orderId Order to re-add
     * @param $userId Current user Id or null
     * @return string|boolean TRUE on success or error string
     */
    public function reAddToCart($orderId, $userId)
    {
        $result = "You cannot access this cart ($orderId, $userId)";
        if ($userId) {
            $ordersTable = ClassRegistry::init('Order');
            $order = $ordersTable->find('first', [
                'conditions' => [
                    'Order.id' => $orderId
                ],
                'contain' => [
                    'OrderItem',
                    'OrderItem.Product'
                ]
            ]);
            if ($order && !empty($order['Order']) && $order['Order']['user_id'] == $userId) {
                $result = 'There are no actual items in this cart';
                if (!empty($order['OrderItem'])) {
                    foreach ($order['OrderItem'] as $orderItem) {
                        unset($orderItem['id']);
                        if (!empty($orderItem['Product'])) {
                            $result = true;
                            $this->addItem($orderItem);
                        }
                    }
                }
            }
        }
        return $result;
    }


}