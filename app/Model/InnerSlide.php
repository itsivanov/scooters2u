<?php

/**
 * Created by PhpStorm.
 * User: root
 * Date: 26.07.16
 * Time: 16:24
 */
class InnerSlide extends AppModel
{
    public $actAs = ['Containable'];

    public $hasMany = [
        'InnerSlideAttachment'
    ];

    public function getSlidesByType($type) {
        $slides = $this->find('all', [
            'conditions' => [
                'type' => $type
            ]
        ]);
        return $slides;
    }

    public function getSlidesTypeId($type, $id) {
        $slides = $this->find('all', [
            'conditions' => [
                'type' => $type,
                'id' => $id
            ]
        ]);
        return $slides;
    }



}