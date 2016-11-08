<?php
App::uses('AppModel', 'Model');
/**
 * Product Model
 *
 */
class TidCategory extends AppModel
{
//    public $hasMany = [
//        'TidsOnCategory'
//    ];
    public $actsAs = array('Containable');
    public $haveOne = array('Tiding');
    public $validate = array(
        'title' => array(
            array(
                'rule' => 'notEmpty',
                'required' => true,
                'message' => 'Title cannot be empty'
            )
        )
    );
    public function getAllActiveForNews()
    {
        $data = Set::extract('/'.$this->name.'/.', $this->find('all', array('conditions' => array('active' => 1),
            'fields' => array('id','title'))));
        return is_array($data) && count($data) >0?$data:false;
    }

    public function getCategory($cat_name)
    {

        $options['joins'] = [
            ['table' => 'tids_on_categories',
                'alias' => 'TidsOnCategory',
                'type' => 'INNER',
                'conditions' => [
                    'TidCategory.id = TidsOnCategory.category_id',
//                    'TidCategory.title =' => $cat_name
                ],
            ]
        ];

        $options['fields'] =[
            'TidCategory.*',
            'TidsOnCategory.*'
        ];

        return $this->find('all', $options);
    }

}
