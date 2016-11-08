<?php

class Option extends AppModel {

    public $name = 'Option';

    public function get(){
        $result = array();
        $options = $this->find('all');
        foreach ($options as $item){
            $result[$item['Option']['key']] = $item['Option']['value'];
        }
        return $result;
    }

    public function getByKey($key = null)
    {
        $option = Set::extract('/Option/.', $this->findByKey($key));

        if ($option){
            return $option[0]['value'];
        }

        return false;
    }

    public function getByGroup($name)
    {
        return $this->find('all', [
            'conditions' => [
                'group' => $name
            ]
        ]);
    }

}