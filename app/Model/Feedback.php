<?php

class Feedback extends AppModel
{

    public $name = 'Feedback';

    public $belongsTo = array(
        'User',
    );

    public $actsAs = [
        'Containable',
    ];


//    public $actsAs = [
//        'Captcha' => [
//            'field' => ['captcha'],
//            'error' => 'Incorrect captcha code value'
//        ]
//    ];

    public $validate = [
        'like' => [
            'rule' => 'notEmpty',
            'required' => true,
            'message' => 'Like cannot be left blank'
        ],
        'improve' => [
            'rule' => 'notEmpty',
            'required' => true,
            'message' => 'Improve cannot be left blank'
        ],
        'comment' => [
            'rule' => 'notEmpty',
            'required' => true,
            'message' => 'Comment cannot be left blank'
        ]
    ];
}