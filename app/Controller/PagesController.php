<?php

App::uses('AppController', 'Controller');
/**
 * Pages Controller
 *
 * @property Publication $Publication
 * @property Static $Static
 * @property Contact $Contact
 * @property Storytab $Storytab
 *
 */
class PagesController extends AppController {

    public $components = [
        'Email',
        'Messenger',
        'Captcha'
    ];

    public $uses = [
        'Category',
        'Contact',
        'Subscriber',
        'Product',
        'Slider',
        'InnerSlideAttachment',
        'InnerSlide',
        'RentalFee',
        'Gallery',
        'Faq',
        'Option',
        'HomePart',
        'Menu'
    ];

    public function beforeFilter()
    {
        parent::beforeFilter();
    }

    public function home()
    {
        if ($this->RequestHandler->isMobile()) {
            $photos = $this->Gallery->find('all');
            $count_photos = round(count($photos)/3);
            $this->set([
                'home' => true,
                'isMobile' => true,
                'slides' => $this->Slider->findActiveSlides(),
                'products' => $this->Product->findActiveProductsWithRental(),
                'howItWorksSlides' => $this->InnerSlide->getSlidesByType('how_it_works'),
                'rentalFees' => $this->RentalFee->find('all'),
                'photos' => $this->Gallery->find('all'),
                'countPhoto' => $count_photos,
                'showInfoBlock' => true,
                'showPreFooter' => $this->HomePart->getOnKey('contact'),
                'phone' => $this->Option->find('first', ['condition' => ['key' => 'phone']]),
            ]);
            $this->autoRender = false;
            $this->render('/Mobile/index');
        }

        $this->set([
            'slides' => $this->Slider->findActiveSlides(),
            'products' => $this->Product->findActiveProductsWithRental(),
            'howItWorksSlides' => $this->InnerSlide->getSlidesByType('how_it_works'),
            'rentalFees' => $this->RentalFee->find('all'),
            'photos' => $this->Gallery->find('all'),
            'showInfoBlock' => true,
            'showPreFooter' => $this->HomePart->getOnKey('contact'),
        ]);

    }

    public function howItWorks()
    {
        $this->set([
            'howItWorksSlides' => $this->InnerSlide->getSlidesByType('how_it_works'),
            'showMob' => true,
            'showPreFooter' => $this->HomePart->getOnKey('contact'),
        ]);

    }

    public function faq()
    {
        $this->set([
            'faqs' => $this->Faq->fegActiveQuestion(),
            'showMob' => true,
            'showPreFooter' => $this->HomePart->getOnKey('contact'),
        ]);
    }

    public function getStarted()
    {
        $product = $this->Product->findProductOnSale();
        $count = count($product);
        $this->set([
            'prodictInfo' => $product,
            'count' => $count
        ]);
    }

    public function display()
    {
        //code
    }

    public function about()
    {
        $slides = $this->InnerSlide->getSlidesByType('about_us');

         //Put attachments in right position
            $slides = array_map(function($slide){
            if(!empty($slide['InnerSlideAttachment'])) {
                $view = new View($this, false);
                $view->ext = '.htm';
                $attachments = $this->InnerSlideAttachment->getAllAttachmentsWithNameKeys($slide['InnerSlideAttachment']);
                $view->set(compact('attachments'));
                $html = $view->render('conditionParts/about_page_in_content', false);
                $slide['InnerSlide']['content'] = $this->putInContent($slide['InnerSlide']['content'], $html);
            }
            return $slide;
        }, $slides);

        $this->set([
            'aboutUsSlides' => $slides,
        ]);
    }

    public function support()
    {

    }

    public function contact()
    {
        $this->set([
            'info' => $this->Option->getByGroup('contacts'),
            'contact' => $this->HomePart->getOnKey('contact'),
            'email' => $this->Option->find('first', ['conditions' => ['key' => 'email']])
        ]);

        if ($this->request->is('post')) {
            $post_contact = [];
            $post_contact['email'] = $this->request->data['Contact']['email'];
            $post_contact['first_name'] = strip_tags($this->request->data['Contact']['first_name']);
            $post_contact['msg'] = strip_tags($this->request->data['Contact']['msg']);
            $post_contact['phone'] = strip_tags($this->request->data['Contact']['phone']);
            if ($this->Contact->save($post_contact)) {
                $this->request->data = null;
                $this->flashMsg('Thank you for contacting', 'info');
            }else{
                $this->flashMsg("Please enter correct info", 'error');
            }
        }
    }


    public function captcha()
    {
        $this->autoRender = false;
        $this->layout='ajax';
        if(!isset($this->Captcha)){
            $this->Captcha = $this->Components->load('Captcha', array(
                'font' => $_SERVER['DOCUMENT_ROOT'].'/app/webroot/sanchez.ttf',
                'width' => 100,
                'height' => 38,
                'characters' => 6,
                'theme' => 'random',
            ));
        }
        $this->Captcha->create();
    }
}