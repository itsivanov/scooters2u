<?php
App::uses('AdminAppModel', 'Admin.Model');
/**
 * User Model
 *
 */
class User extends AdminAppModel
{
    public $useTable = 'users';
    public $validate = [
        'old_password' => [
            'notEmpty' => [
                'rule'    => ['notEmpty'],
                'message' => 'This field can not be empty'
             ],
            'matchPassword' => [
                'rule'    => ['validateMatchPassword'],
                'message' => 'Password not match'
             ],
        ],
        'new_password' => [
            'notEmpty' => [
                'rule'    =>  ['notEmpty'],
                'message' => 'This field can not be empty'
            ],
            'alphaNumeric' => [
                'rule' => ['notEmpty'],
                'required' => true,
                'message'  => 'Only letters and integers, min 3 characters'
            ],
        ],
        'confirm_password' => [
            'rule'    => ['validateConfirmPassword'],
            'message' => 'Passwords do not match'
        ],
        'email' => [
            'required' => [
                'rule'    => ['email', true],
                'message' => 'Please provide a valid email address.'
            ],
            'unique' => [
                'rule'    => ['isUniqueEmail'],
                'message' => 'This email is already in use',
            ],
            'between' => [
                'rule' => ['between', 6, 35],
                'message' => 'Email must be between 6 to 35 characters'
            ]
        ]
    ];
    /**
     * @var string admin name
     */
    private $userName = 'admin';

    /**
     * Match old passwords validation
     * @return bool
     */

    public function validateMatchPassword()
    {
        $adminUser = $this->findByUsername($this->userName);
        $password = $adminUser['User']['password'];
        return AuthComponent::password($this->data['User']['old_password'])  == $password;
    }
    /**
     * Match passwords validation
     * @return bool
     */

    public function savePassword($data)
    {
        $adminUser = $this->findByUsername($this->userName);
        $this->id = $adminUser['User']['id'];
        $data['User']['password'] = AuthComponent::password($data['User']['new_password']);

        return $this->save($data);
    }

    function isUniqueEmail($check) {

        $email = $this->find([
                    'fields'     => ['User.id'],
                    'conditions' => ['User.email' => $check['email']]
                ]);

        if($email){
            if($this->data[$this->alias]['id'] == $email['User']['id']){
                return true;
            }else{
                return false;
            }
        }else{
            return true;
        }
    }

}
