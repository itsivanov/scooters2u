<?php
App::uses('AdminAppModel', 'Admin.Model');
/**
 * Page Model
 *
 */
class AdminPage extends AdminAppModel
{
    public $useTable = 'pages';
    /**
     * Validation rules
     * @var array
     */
    public $validate = array(
        'title' => array(
            'rule' => 'notEmpty',
            'message' => 'Field must not be empty'
        ),
        'key' => array(
           'isUnique' => array(
                'rule' => 'isUnique',
                'message' => 'The key must be unique'
           ),
           'match' => array(
                'rule' => '/^[-_a-zA-Z0-9]+$/i',
                'message' => 'Field can only contain letters, numbers and symbols "_" , "-".'
           ),
           'between' => array(
                'rule' => array('between', 3, 128),
                'message' => 'Field  is between 3 to 128 characters'
           )
        )
	);
}
