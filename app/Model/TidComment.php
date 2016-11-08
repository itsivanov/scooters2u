<?php
App::uses('AppModel', 'Model');
/**
 * Product Model
 *
 */
class TidComment extends AppModel
{
    public $validate = array(
        'email' => array(
            array(
                'rule' => 'notEmpty',
                'required' => true,
                'message' => 'Email cannot be empty'
            ),
            array(
                'rule' => 'email',
                'required' => true,
                'message' => 'Invalid email format'
            )
        ),
        'name' => array(
            array(
                'rule' => 'notEmpty',
                'required' => true,
                'message' => 'Name cannot be empty'
            )
        ),
        'content' => array(
            array(
                'rule' => 'notEmpty',
                'required' => true,
                'message' => 'Content cannot be empty'
            ),
        )
    );
    public $actsAs = array('Containable');
    public $belongsTo = array(
        'Tiding' => array(
            'counterCache' => array(
                'tidcomment_count' => array('TidComment.active' => 0),
                'active_tidcomment_count' => array('TidComment.active' => 1)
            )
        )
    );
    public function getListOfLastComments()
    {
        $data = $this->find('all', array('conditions' => array('TidComment.active' => 1),'limit' => 5,'order'=>array('TidComment.created'=>'DESC')));
        return (is_array($data) && count($data) > 0)?$data:false;

    }

    public function update($data)
    {
        $this->save($data);
    }

}
