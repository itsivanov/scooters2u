<?php
App::uses('Component', 'Controller');

// composer autoloader for use component
require_once ROOT . DS . 'app' . DS . 'Vendor' . DS . 'composer' . DS . 'vendor' . DS . 'autoload.php';

/**
 * Class ShippingComponents
 */
class ShippingComponent extends Component
{
    private $controller = null;

//    public $components = ['Session'];

    protected $accessKey;
    protected $userId;
    protected $password;

    public function initialize(Controller $controller, $settings = array())
    {
        $this->controller =& $controller;
    }

    public function loadApiCredentials()
    {
        if (!isset($this->accessKey)) {
            Configure::load('ups');
            $this->userId = Configure::read('ups.userId');
            $this->password = Configure::read('ups.password');
            $this->accessKey = Configure::read('ups.accessKey');
        }
    }

    /**
     * Calculate shipping rate
     *   ["OrderShippingInfo"]=>
            array(10) {
                ["id"]=>
                    string(2) "24"
                ["order_id"]=>
                    string(2) "27"
                ["first_name"]=>
                    string(4) "Mimi"
                ["last_name"]=>
                    string(6) "Mimimi"
                ["address_1"]=>
                    string(19) "100-150 SW Canal St"
                ["address_2"]=>
                    string(19) "100-150 SW Canal St"
                ["zip"]=>
                    string(5) "33166"
                ["country"]=>
                    NULL
                ["state"]=>
                    string(7) "Florida"
                ["phone"]=>
                    string(15) "199999999999999"
                ["email"]=>
                    string(15) "3xdimon@ukr.net"
            }
     *   ["OrderItem"]=>
            array(1) {
                [0]=>
                    array(6) {
                        ["id"]=>
                            string(2) "31"
                        ["order_id"]=>
                            string(2) "27"
                        ["product_id"]=>
                            string(1) "4"
                        ["quantity"]=>
                            string(1) "1"
                        ["properties"]=>
                            NULL
                        ["Product"]=>
                            array(2) {
                        ["title"]=>
                            string(8) "testprod"
                        ["price"]=>
                            string(5) "55.56"
                    }
                }
            }
     * @param $order
     * @param $service
     */
    public function calculateShippingRate($order, $service = false)
    {
        $this->loadApiCredentials();

        $rate = new \Ups\Rate(
            $this->accessKey,
            $this->userId,
            $this->password
        );

        /** @var Option $Option */
        $Option = ClassRegistry::init('Option');
        /** @var State $State */
        $State = ClassRegistry::init('State');

        $addressFrom = $Option->getByKey('address');
        $postalCode = $Option->getByKey('postal_code');
        $addressFrom = strip_tags($addressFrom);


        $shipment = new \Ups\Entity\Shipment();

        $shipperAddress = $shipment->getShipper()->getAddress();
        $shipperAddress->setPostalCode($postalCode);

        $address = new \Ups\Entity\Address();
        $address->setPostalCode($postalCode);
        $address->setAddressLine1($addressFrom);
        $shipFrom = new \Ups\Entity\ShipFrom();
        $shipFrom->setAddress($address);

        $shipment->setShipFrom($shipFrom);

        $shipTo = $shipment->getShipTo();
//            $shipTo->setCompanyName('Delivery order');
        $shipToAddress = $shipTo->getAddress();
        $shipToAddress->setPostalCode($order['OrderShippingInfo']['zip']);
        $shipToAddress->setAddressLine1($order['OrderShippingInfo']['address_1']);
        $shipToAddress->setAddressLine2($order['OrderShippingInfo']['address_2']);
        $shipToAddress->setCountryCode('US');
//        $shipToAddress->setStateProvinceCode($State->getStateCode($order['OrderShippingInfo']['state']));
//        $shipToAddress->setCity($order['OrderShippingInfo']['state']);

        foreach ($order['OrderItem'] as $OrderItem) {
            for ($i = 0; $i < $OrderItem['quantity']; $i++) {
                $package = new \Ups\Entity\Package();
//                $package->getPackagingType()->setCode(\Ups\Entity\PackagingType::PT_PACKAGE);
                $package->getPackageWeight()->setWeight($OrderItem['Product']['weight']);

                // in future if need
                // 1 =      1:1:1
                // 2 =      1x2:1:1
                // 3-4 =    1x2:1x2:1
                // 5-8 =    1x2:1x2:1x2

                $dimensions = new \Ups\Entity\Dimensions();
                $dimensions->setHeight($OrderItem['Product']['height']);
                $dimensions->setWidth($OrderItem['Product']['width']);
                $dimensions->setLength($OrderItem['Product']['length']);

                $unit = new \Ups\Entity\UnitOfMeasurement;
                $unit->setCode(\Ups\Entity\UnitOfMeasurement::UOM_IN);

                $dimensions->setUnitOfMeasurement($unit);
                $package->setDimensions($dimensions);

                $shipment->addPackage($package);
            }
        }

        if ($service) {
            try {
                $shipment->getService()->setCode($service);
                return $rate->getRate($shipment);
            } catch (Exception $e) {
                return false;
            }
        }

        $rates = array(
            \Ups\Entity\Service::S_AIR_1DAYEARLYAM => null,
            \Ups\Entity\Service::S_AIR_1DAY => null,
            \Ups\Entity\Service::S_AIR_1DAYSAVER => null,

            \Ups\Entity\Service::S_AIR_2DAYAM => null,
            \Ups\Entity\Service::S_AIR_2DAY => null,

//            \Ups\Entity\Service::S_STANDARD => null,
            \Ups\Entity\Service::S_GROUND => null,
        );

        $valid = 0;

        foreach ($rates as $service => $_nullAble) {
            try {
                $shipment->getService()->setCode($service);
                $rates[$service] = $rate->getRate($shipment);
                $valid++;
            } catch (Exception $e) {
                $rates[$service] = $e->getMessage();
            }
        }

        if ($valid == 0) {
            $messages = array_filter($rates, function ($item) {
                return is_string($item);
            });
            return is_array($messages) ? reset($messages) : '';
        }

        return array_filter($rates, function ($item) {
            return is_object($item);
        });
    }

}