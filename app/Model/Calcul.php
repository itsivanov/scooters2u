<?php

App::uses('AppModel', 'Model');


class Calcul extends AppModel {

    public $name = 'Calcul';


    public function details($data)
    {
        $Accessory = ClassRegistry::init('Accessory');

        $sum['sum'] = $data['num'] * $data['price'];

        if ($data['cal1'] && $data['cal2']){
            $datetime1 = new DateTime($data['cal1']);
            $datetime2 = new DateTime($data['cal2']);
            if ($datetime1 < $datetime2 ) {
                $interval = $datetime1->diff($datetime2);
                $days = $interval->days;
                $sum['sum'] *= $days;
            }else{
                $days = 1;
            }
        }else{
            $days = 1;
        }

        $i = 0;

        foreach ($data['Accessory'] as $item) {
            if(isset($item['number'])){
                if ($item['number'] != 0 ){
//                    $data['access'][$i][] = $this->Accessory->getOnId($item['id']);
//                    $data['access_numb'][$i] = $item['number'];

                    for ($j=0;$j< $item['number'];$j++){
                        $data['access'][$i][] = $Accessory->getOnId($item['id']);
                    }
                }
                $i++;
            }

        }
        unset($data['Accessory']);

        //for item total price
        $data['item_sum'] = $data['num'] * $data['price'] * $days;

        return $data;
    }

    public function orderProductAccessories($days)
    {
        for ($i = 0; $i < count($_SESSION['accessory']); $i++) {

            if (isset($_SESSION['order']['size'][$i]['size'])) {
                $_SESSION['accessory'][$i]['Accessory']['size'] = $_SESSION['order']['size'][$i]['size'];
            }
            $days = $days != 0 ? $days : 1;
            //increase access on days
            if (isset($_SESSION['accessory'][$i]['Accessory']['price'])) {
                $arrAcc = [];
                $_SESSION['accessory'][$i]['Accessory']['total'] = $_SESSION['accessory'][$i]['Accessory']['price'] * $days;

            }
        }
    }

    //nearest filter key array
    public function nearest($arr, $days)
    {

        $less = array_filter($arr, function($a) use ($days){
            return $a['ProductRental']['number'] <= $days;
        });
        $result = array_pop($less);

        if($result['ProductRental']['number'] == 0 || $result['ProductRental']['number'] ==1){
            $res = $result['ProductRental']['value'];
        }else{

            $res = $result['ProductRental']['value'] / $result['ProductRental']['number'];
        }

        return $res;
    }
}
