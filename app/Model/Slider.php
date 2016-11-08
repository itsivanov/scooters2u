<?php
App::uses('AppModel', 'Model');

/**
 * Category Slider
 *
 */
class Slider extends AppModel {


    public $useTable = 'slides';

    /**
     *  Search all elements of table vt_slides
     * @return array
     */

    public function findActiveSlides()
    {
        return $this->find('all', [
           'conditions' => [
               'active' => 1
           ]
        ]);
    }

    /**
     *  For Admin Panel
     */

    public function findAllSliders()
    {
        return $this->find('all');
    }

    /**
     * Search element of table vt_slides for edit of admin panel
     * @param $id
     * @return integer
     */
    public function findId($id)
    {
        return $this->find('first', [
            'conditions' => [
                'id' => $id
            ]
        ]);
    }

    /**
     * Add slide of admin panel
     * @param $data integer
     * @return boolean
     */
    public function addSlider($data)
    {
        $this->save($data);
    }

    /**
     *  Edit elements slides of page Home -> first section
     * @param $data
     * @return boolean
     */
    public function update($data)
    {
       return $this->save($data);
    }



}
