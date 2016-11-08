<?php
App::uses('AppModel', 'Model');
/**
 * Product Model
 *
 */
class Tag extends AppModel
{
//    public $hasMany = [
//        'TidsOnCategory'
//    ];
//    public $actsAs = array('Containable');
//    public $haveOne = array('Tiding');
//    public $validate = array(
//        'title' => array(
//            array(
//                'rule' => 'notEmpty',
//                'required' => true,
//                'message' => 'Title cannot be empty'
//            )
//        )
//    );

//
//    public function getCategory($tag_name)
//    {
//
//        $options['joins'] = [
//            ['table' => 'tags_tidings',
//                'alias' => 'TagTiding',
//                'type' => 'INNER',
//                'conditions' => [
//                    'Tag.id = TagTiding.tag_id',
////                    'Tid.title =' => $tag_name
//                ],
//            ]
//        ];
//
//        $options['fields'] =[
//            'Tag.*',
//            'TagTiding.*'
//        ];
//
//        return $this->find('all', $options);
//    }

}
