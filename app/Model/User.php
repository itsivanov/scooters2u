<?php
App::uses('Role', 'Model');
App::uses('Security', 'Utility');
App::uses('CakeSession', 'Model/Datasource');

/**
 * User Model
 *
 */
class User extends AppModel
{
    public $actAs = ['Containable'];
    private $adminUserName = 'admin';

    public $actsAs = [
        'Containable'
    ];

//    public $recursive = 1;

    public $hasOne = [
        'UserInfo' => [
            'className'    => 'UserInfo',
            'dependent'    =>  true,
            'foreignKey'   => 'user_id'
        ],
        'UserBillingInfo' => [
            'className'    => 'UserBillingInfo',
            'dependent'    =>  true,
            'foreignKey'   => 'user_id'
        ],
        'UserShippingInfo' => [
            'className'    => 'UserShippingInfo',
            'dependent'    =>  true,
            'foreignKey'   => 'user_id'
        ]
    ];

    public $hasMany = [
        'Order'
    ];

    public $validate = [
        'id' => [
            'rule' => 'isUnique',
            'message' => "Don't try to fool me!",
            'on' => 'create'
        ],
        'email' => [
            ['rule' => 'notEmpty', 'message' => 'E-mail cannot be empty'],
            ['rule' => 'isUnique', 'message' => 'This e-mail is already taken'],
            ['rule' => 'email',    'message' => 'This e-mail has incorrect format!']
        ],
//        'username' => [
//            ['rule' => 'notEmpty', 'message' => 'Username cannot be empty'],
//            ['rule' => 'isUnique', 'message' => 'This username is already taken']
//        ],
        'password' => [
            ['rule' => 'notEmpty', 'message' => 'Password cannot be empty'],
            ['rule' => ['minLength', 5], 'message' => "Your password must be at least 5 characters."],
//            ['rule' => '/[A-Za-z]+/', 'message' => 'Your password must be at least 8 characters  and contain at least one character and one numeric value.'],
//            ['rule' => '/\d+/', 'message' => 'Your password must be at least 8 characters  and contain at least one character and one numeric value.'],
            ['rule' => ['passCompare'], 'message' => 'The passwords do not match']
        ]
    ];


    public function beforeSave($options = array())
    {
        if (isset($this->data[$this->alias]['id'], $this->data[$this->alias]['old_password'], $this->data[$this->alias]['password'])) {
            if (!$this->checkOldPassword($this->data[$this->alias]['old_password'])) {
                $this->validationErrors['old_password'] = 'Incorrect current password';
                return false;
            }
        }

        if (isset($this->data[$this->alias]['password'], $this->data[$this->alias]['confirm_password'])) {

            if (!$this->validateConfirmPassword()){
                $this->validationErrors['confirm_password'] = 'The passwords do not match';
                return false;
            }
        }

        if (isset($this->data[$this->alias]['password'])) {
            $this->data[$this->alias]['password'] = Security::hash($this->data[$this->alias]['password'], null, true);
        }

        return parent::beforeSave($options);
    }

    public function passCompare()
    {
        if (isset($this->data[$this->alias]['password'], $this->data[$this->alias]['confirm_password'])) {
            return ($this->data[$this->alias]['password'] === $this->data[$this->alias]['confirm_password']);
        } else {
            return false;
        }
    }

    public function validateConfirmPassword()
    {
        return $this->data['User']['password'] == $this->data['User']['confirm_password'];
    }

    public function checkOldPassword($oldPassword)
    {
        $oldPass = $this->field('password', array('User.id' => $this->data[$this->alias]['id']));
        $newPass = Security::hash($oldPassword, null, true);
        return ($oldPass === $newPass);
    }

    public function getUserBy($byThis, $contain = [])
    {
        $column = (is_numeric($byThis)) ? 'id' : 'email';
        $get = $this->find('first', array(
            'contain' => $contain,
            'conditions' => array(
                "User.$column" => $byThis
            ), 'fields' => '*'
        ));
        return $get;
    }

    public function getUser($uID)
    {
        $get = $this->find('first', [
            'conditions' => ['User.id' => $uID],
            'recursive' => 0
        ]);

        return $get;
    }

    public function updateLastUserLogin($userId)
    {
       if ($userId){
           $this->id = $userId;
           return $this->saveField('last_login', date("Y-m-d H:i:s"));
       }
    }

    public function getUsersStatistic($from)
    {
        $settings  = [
            'conditions' => [
                'User.created >=' => $from
            ],
            'contain' =>[
                'fields' => [
                    'User.id'
                ],
                'Order' => [
                    'fields' => [
                        'id',
                        'amount',
                        'used_poins',
                        'order_status_id'
                    ]
                ]

            ]
        ];

        $list = $this->find('all', $settings);




        return $list;
    }

    public function updateUserPointsBalance($amount, $uId, $potgCoefficient)
    {
        $user = $this->getUserBy($uId);
        $newBalance = $user['User']['point_balance'];
        if (!empty($amount['used'])) {
             $newBalance -= $amount['used'];
        }

        if (!empty($amount['amount'])) {
            $newBalance += floor(($amount['amount'] - $amount['shipping']) * $potgCoefficient);
        }

        $this->id = $uId;

        return $this->saveField('point_balance', $newBalance);
    }

    public function saveAdminPassword($data)
    {
        $adminUser = $this->findByUsername($this->adminUserName);
        $this->id = $adminUser['User']['id'];
        $this->set('id', $adminUser['User']['id']);

        if (!$this->checkOldPassword($data['User']['old_password'])) {
            $this->validationErrors['old_password'] = 'Incorrect current password';
            return false;
        }

        $data['User']['password'] = Security::hash($data['User']['new_password'], null, true);
        $data['User']['confirm_password'] = Security::hash($data['User']['confirm_password'], null, true);

        return $this->save($data);
    }
}