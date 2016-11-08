<?php

/**
 * Created by PhpStorm.
 * User: root
 * Date: 27.07.16
 * Time: 18:11
 */
class Faq extends AppModel
{
    public function getAll()
    {
        return $this->find('all');
    }
    public function fegActiveQuestion()
    {
        return $this->find('all', [
           'conditions' => [
               'active' => 1
           ]
        ]);
    }

    public function getOnId($id)
    {
        return $this->find('all', [
            'conditions' => [
                'id' => $id
            ]
        ]);
    }


}