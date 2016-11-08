<?php
App::uses('AdminAppModel', 'Admin.Model');
/**
 * Contact Model
 *
 */
class Contact extends AdminAppModel
{
    public $useTable = 'contacts';

    public $validate = array(
        'name' => array(
            'rule'    => array('minLength', '3'),
            'message' => 'Minimum 3 characters long'
        ),
    );

    public function countNewMessages()
    {
        $contactCount = $this->find('count', array(
            'conditions' => array('viewed' => 0)
        ));

//        $assignmentsCount = ClassRegistry::init('ClaimRequest')->find('count', array(
//            'conditions' => array('viewed' => 0)
//        ));

        return $contactCount;

    }

}
