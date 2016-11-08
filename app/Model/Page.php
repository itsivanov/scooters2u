<?php
App::uses('AppModel', 'Model');
/**
 * Page Model
 *
 */
class Page extends AppModel
{

    public function getPage($key)
    {
        $data = $this->find('first', array(
            'conditions' => array(
                'key' => $key,
                'active' => 1
            ),
//            'fields' => array(
//                'title', 'content', 'meta_keywords', 'meta_description'
//            )
        ));
        return $data;
    }

    public function getAllActivePages()
    {
         $data = $this->find('all', array(
            'conditions' => array(
                'active' => 1,
            ),
            'fields' => array(
                'key', 'title'
            ),
            'order' => array(
                'title'
            ),
        ));
        $results = array();
        foreach ($data as $item) {
            $url = $item['Page']['key'];
            $results[$url] = $item['Page']['title'];
        }
        return $results;
    }

}
