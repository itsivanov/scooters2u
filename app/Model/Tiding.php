<?php
App::uses('AppModel', 'Model');
/**
 * Product Model
 *
 */
class Tiding extends AppModel
{
    public $uses = [
        "Contact",
        "TidsOnCategory",
        "Tag"
    ];

    public $validate = array(
        'title' => array(
            array(
                'rule' => 'notEmpty',
                'required' => true,
                'message' => 'Title cannot be empty'
            ),
            array(
                'rule' => 'isUnique',
                'required' => true,
                'message' => 'This title is already taken',
                'on'=>'create'
            )
        ),
        'slug' => array(
            array(
                'rule' => 'notEmpty',
                'required' => true,
                'message' => 'Seo url cannot be empty'
            ),
            array(
                'rule' => 'isUnique',
                'required' => true,
                'message' => 'This seo url is already taken'
            )
        )
    );
    public $actsAs = array('Containable');
    public $belongsTo = array('TidCategory' => array('fields' =>array('title')));
    public $hasMany = array(
        'TidComment' => array(
            'className' => 'TidComment',
            'conditions' => array('TidComment.active' => '1'),
            'order' => 'TidComment.created DESC'
        ),
        'TidCategories' => array(
            'className' => 'TidsOnCategory',
        ),
        'TagsTidings' => [
            'className' => 'TagsTidings',
        ],
    );
    public function getActiveListNews()
    {
        $data = $this->find('all', array('conditions' => array('Tiding.active' => 1)));
        return (is_array($data) && count($data) > 0)?$data:false;

    }
    public function getListOfLastNews()
    {

        $data = $this->find('all', array('conditions' => [ 'Tiding.active' => 1 ] ,'limit' => 5,'order'=>array('Tiding.created'=>'DESC')));
        return (is_array($data) && count($data) > 0)?$data:false;

    }

    public function getNewsBySlug($slug)
    {
        if (!isset($slug) || empty($slug) || strlen((string)$slug) == 0 ) { return false; }
        $data = $this->find('first', array('conditions' => array('Tiding.active' => 1,'Tiding.slug' => $slug)));
        return (is_array($data) && count($data) > 0)?$data:false;
    }

    public function getTidOnCategory($slug)
    {
        $category = [];
        $arr = [];
        //SELECT * FROM vt_tidings as tid INNER JOIN vt_tids_on_categories as toc ON tid.id = toc.tiding_id
        $options['joins'] = [
            ['table' => 'tids_on_categories',
                'alias' => 'TidsOnCategory',
                'type' => 'INNER',
                'conditions' => [
                    'Tiding.id = TidsOnCategory.tiding_id',
                    'Tiding.slug = ' => $slug
                ],
            ]
        ];

        $options['fields'] =[
                'TidsOnCategory.category_id'
        ];

        $data =  $this->find('all', $options);

        foreach ( $data as $item) {
            $arr[] = $this->TidCategory->find('all', [
                'conditions' => [
                    'id' => $item['TidsOnCategory']['category_id']
                ]
            ]);
        }
        foreach ($arr as $items) {
            foreach ($items as $item) {
                $category[] = $item['TidCategory'];
            }
        }

        return $category;
    }

    public function getTidOnId($id)
    {
        $category = [];
        $arr = [];
        //SELECT * FROM vt_tidings as tid INNER JOIN vt_tids_on_categories as toc ON tid.id = toc.tiding_id
        $options['joins'] = [
            ['table' => 'tids_on_categories',
                'alias' => 'TidsOnCategory',
                'type' => 'INNER',
                'conditions' => [
                    'Tiding.id = TidsOnCategory.tiding_id',
                    'Tiding.id = ' => $id
                ],
            ]
        ];

        $options['fields'] =[
            'TidsOnCategory.category_id'
        ];

        $data =  $this->find('all', $options);

        foreach ( $data as $item) {
            $arr[] = $this->TidCategory->find('all', [
                'conditions' => [
                    'id' => $item['TidsOnCategory']['category_id']
                ]
            ]);
        }
        foreach ($arr as $items) {
            foreach ($items as $item) {
                $category[] = $item['TidCategory'];
            }
        }

        return $category;
    }

    public function getNewsOnCategory($cat_id)
    {
        //SELECT * FROM vt_tidings as tid INNER JOIN vt_tids_on_categories as toc ON tid.id = toc.tiding_id
        $options['joins'] = [
            ['table' => 'tids_on_categories',
                'alias' => 'TidsOnCategory',
                'type' => 'INNER',
                'conditions' => [
                    'Tiding.id = TidsOnCategory.tiding_id',
                    'TidsOnCategory.category_id = ' => $cat_id
                ],
            ]
        ];

        $options['fields'] =[
            'Tiding.*'
        ];

        return $this->find('all', $options);
    }

    public function getTidOnTags($slug)
    {

        //SELECT * FROM vt_tidings as tid INNER JOIN vt_tids_on_categories as toc ON tid.id = toc.tiding_id
        $options['joins'] = [
            ['table' => 'tags_tidings',
                'alias' => 'TagTiding',
                'type' => 'INNER',
                'conditions' => [
                    'Tiding.id = TagTiding.tiding_id',
                    'Tiding.slug = ' => $slug,
                ],
            ]
        ];

        $options['fields'] =[
            'TagTiding.tag_id'
        ];

        return  $this->find('all', $options);
    }

    public function getTagOnIdNews($id)
    {

        //SELECT * FROM vt_tidings as tid INNER JOIN vt_tids_on_categories as toc ON tid.id = toc.tiding_id
        $options['joins'] = [
            ['table' => 'tags_tidings',
                'alias' => 'TagTiding',
                'type' => 'INNER',
                'conditions' => [
                    'Tiding.id = TagTiding.tiding_id',
                    'Tiding.id = ' => $id,
                ],
            ]
        ];

        $options['fields'] =[
            'TagTiding.*'
        ];

        return  $this->find('all', $options);
    }

    public function getNewsOnTag($tag_id)
    {
        //SELECT * FROM vt_tidings as tid INNER JOIN vt_tids_on_categories as toc ON tid.id = toc.tiding_id
        $options['joins'] = [
            ['table' => 'tags_tidings',
                'alias' => 'TagsTidings',
                'type' => 'INNER',
                'conditions' => [
                    'Tiding.id = TagsTidings.tiding_id',
                    'TagsTidings.tag_id = ' => $tag_id,
                    'Tiding.active = ' => 1,

                ],
            ]
        ];

        $options['fields'] =[
            'Tiding.*'
        ];

        return $this->find('all', $options);
    }




}
