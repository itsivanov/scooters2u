<?php
App::uses('AdminAppController', 'Admin.Controller');
/**
 *@property InnerSlide $InnerSlide
 *@property InnerSlideAttachment $InnerSlideAttachment
 */
class AdminAboutController extends AdminAppController
{

    public $uses = ['InnerSlide', 'InnerSlideAttachment'];

    public function index()
    {
        $this->setActiveMenu(array('blog'));

        $about = $this->InnerSlide->getSlidesByType('about_us');
        $this->set([
           'infoAbout' => $about
        ]);
    }


    public function edit()
    {
        $this->setActiveMenu(array('blog'));

        if ($this->request->data){
            $this->InnerSlide->save($this->request->data['InnerSlide']);
            foreach ($this->request->data['InnerSlideAttachment'] as $item) {
                $this->InnerSlideAttachment->save($item);
            }
        }
        $about = $this->InnerSlide->getSlidesTypeId('about_us', $this->request->id);

        $this->set([
            'infoAbout' => $about[0]
        ]);
    }



}