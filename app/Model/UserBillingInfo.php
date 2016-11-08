<?php
App::uses('Role', 'Model');

/**
 * Userinfo Model
 *
 */
class UserBillingInfo extends AppModel
{
    public $belongsTo = [
        'User'
    ];

    public $validate = [
//        'first_name' => [
//            ['rule' => 'notEmpty', 'message' => 'First name cannot be empty'],
//            ['rule' => ['minLength', 3], 'message' => "First name must be at least 3 characters."]
//        ],
//        'last_name'  => [
//            ['rule' => 'notEmpty', 'message' => 'Last name cannot be empty'],
//            ['rule' => ['minLength', 3], 'message' => "Last name must be at least 3 characters."]
//        ]
    ];
}