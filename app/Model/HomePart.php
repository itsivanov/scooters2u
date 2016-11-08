<?php

/**
 * Created by PhpStorm.
 * User: root
 * Date: 26.07.16
 * Time: 12:24
 */
class HomePart extends AppModel
{
    public function findActiveByKeyAndResort()
    {
        $resortedArray = [];
        $parts = $this->find('all', [
            'conditions' => [
                'active' => '1'
            ]
        ]);

        if(!empty($parts)) {
            foreach ($parts as $part) {
                $resortedArray[$part['HomePart']['key']] = $part;
            }
        }

        return $resortedArray;
    }

    public function getPartsOfAdmin()
    {
        return $this->find('all', [
            'conditions' => [
                'admin_edit' => '1'
            ]
        ]);
    }

    public function getPartsOnId($id)
    {
        return $this->find('all', [
            'conditions' => [
                'id' => $id,
                'admin_edit' => '1'
            ]
        ]);
    }

    public function getOnKey($key)
    {
        return $this->find('all', [
            'conditions' => [
                'key' => $key,
                'admin_edit' => '1'
            ]
        ]);
    }
}