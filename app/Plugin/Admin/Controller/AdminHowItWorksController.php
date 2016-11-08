<?php
App::uses('AdminAppController', 'Admin.Controller');
/**
 *@property Gallery $Gallery
 * @property  ProductRental $ProductRental
 */

class AdminHowItWorksController extends AdminAppController
{
    public $uses = ['InnerSlide'];

    public function index()
    {
        $this->setActiveMenu(array('blog'));

        $itworks = [];
        $howitwork = $this->InnerSlide->getSlidesByType('how_it_works');
        foreach ($howitwork as $item) {
            $itworks[] = $item['InnerSlide'];
        }

        $this->set([
           'infoWorks' => $itworks
        ]);
    }


    public function edit()
    {
        $this->setActiveMenu(array('blog'));

        if ($this->request->data){
            unset($this->request->data['InnerSlide']['image']);
            $this->InnerSlide->save($this->request->data['InnerSlide']);

        }
        $howitworks = $this->InnerSlide->getSlidesTypeId('how_it_works', $this->request->id);
        foreach ($howitworks as $item) {
            $itworks = $item['InnerSlide'];
        }
        $this->set([
            'infoWorksOne' => $itworks
        ]);


    }


}