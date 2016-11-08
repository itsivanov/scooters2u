<?php
/**
 * Static content controller.
 *
 * This file will render views from views/pages/
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

App::uses('AppController', 'Controller');

/**
 * Static content controller
 *
 * Override this controller by placing a copy in controllers directory of an application
 *
 * @package       app.Controller
 * @link http://book.cakephp.org/2.0/en/controllers/pages-controller.html
 */
define('BLOG_MAIN_PAGINATE', 4);

class TidingsController extends AppController
{

    /**
     * This controller does not use a model
     *
     * @var array
     */
    public $uses = [
        'Tiding',
        'TidCategory',
        'TidComment',
        'Contact',
        'Tag',
        'Menu'
    ];



    public $components = array('RequestHandler', 'ArrayWalk', 'Paginator');
    public $helpers = array('Js' => array('Jquery'), 'Html', 'Form', 'Time', 'Text');
    public $paginate = array(
        'limit' => BLOG_MAIN_PAGINATE,
        'order' => array(
            'Tiding.created' => 'asc'
        )
    );

    public function beforeRender()
    {
        parent::beforeRender();
    }

    public function index()
    {
        $view = new View($this, false);
        $view->ext = '.htm';

        $active_sort = 'last';
        $this->paginate = array(
            'limit' => BLOG_MAIN_PAGINATE,
            'order' => array(
                'Tiding.created' => 'DESC'
            )
        );

        $this->Paginator->settings = $this->paginate;
        $news = $this->Paginator->paginate('Tiding', array('Tiding.active' => 1));
        $count = $this->Tiding->find('count', array('Tiding.active' => 1));
        if($this->request->is('ajax')) {
//            exit('hello');
            $counter = 0;
            $this->GoodAjax->ajaxResponse = [
                'news' => array_map(function($item) use ($view, &$counter){
                    $view->set(compact('item', 'counter'));
                    $fade = $counter == 0 || !($counter%2)? 'fadeInLeft': 'fadeInRight';
                    $item['Tiding']['htmlContent'] = '
                    <div class="box animated ' . $fade . '">
                        <div class="wrapp-img">
                            <div style="background-image: url(\'/thumbs/550x475' . $item["Tiding"]["img"] . '\')" class="fix-center-img"></div>
                        </div>
                        <div class="text-post">
                            <h2>' . $item["Tiding"]["title"] . '</h2>
                            <span class="date-post">'.date("M j,Y", strtotime($item['Tiding']['created'])).'</span>
                            
                            <div class="wrapp-txt">
                            ' . substr($item["Tiding"]["content"], 250) .'
                            </div>
                            <a class="more-btn" href="/news/'. $item["Tiding"]["slug"].'">more</a>
                        </div>
                    </div>';

                    $counter++;
                    return $item;
                }, $news),
                'count' => $count,
                'currentPage' => $this->request->query('page'),
                'perPage' => BLOG_MAIN_PAGINATE
            ];
            $this->GoodAjax->sendAjax();

        } else {
            $this->set(array(
                'news' => $news,
                'newsCount' => $count,
                'active_sort' => $active_sort,
                'tidcategories' => $this->TidCategory->getAllActiveForNews(),
                'footer' => $this->Menu->find('all', ['conditions'=> ['site_menu_id' => 4, 'active' => 1]])
            ));
        }
    }

    public function view()
    {
        $tags_items = [];
        $tag_arr = [];

        //post contact
        if (!empty($this->request->data['Contact'])){
            $this->loadModel("Subscribe");
            if($this->Subscribe->save($this->request->data['Contact'])){
                $this->flashMsg('Thank you for contacting', 'info');
            }else{
                $this->flashMsg("Please enter correct info", 'error');
            }
        }

        //post comment
        if (isset($this->request->data['comment'])){
            $this->request->data['comment']['name'] = strip_tags($this->request->data['comment']['name']);
            $this->request->data['comment']['content'] = strip_tags($this->request->data['comment']['content']);

            $this->request->data['comment']['created'] = date("Y-m-d H:i:s");
                if($this->TidComment->save($this->request->data['comment'])){
                    $this->flashMsg('Thank you for comment  ', 'info');
                }else{
                    $this->flashMsg("Please enter correct info", 'error');
                }
        }
        $category = $this->Tiding->getTidOnCategory($this->request->params['slug']);

        $tags = $this->Tiding->getTidOnTags($this->request->params['slug']);

        foreach ( $tags as $item) {

            $tag_arr[] = $this->Tag->find('all', [
                'conditions' => [
                    'id' => $item['TagTiding']['tag_id'],
                    'active' => 1
                ]
            ]);
        }
        foreach ($tag_arr as $items) {
            foreach ($items as $item) {
                $tags_items[] = $item['Tag'];
            }
        }
        $this->set([
            'news' => $this->Tiding->getNewsBySlug( $this->request->params['slug']),
            'categories' => $this->TidCategory->getAllActiveForNews(),
            'last_articles' => $this->Tiding->find('all', [
                                                    'conditions' => [
                                                        'Tiding.active' => 1,
                                                        'Tiding.slug !=' => $this->request->params['slug']
                                                    ],
                                                    'limit' => 3,'order'=>['Tiding.created'=>'DESC']]),
            'category' => $category,
            'tags' => $tags_items
        ]);

    }


    public function category()
    {

        if (!empty($this->request->query['cat'])) {
            $cat = urldecode($this->request->query['cat']);
            $category = $this->TidCategory->find('first', array(
                'conditions' => array('TidCategory.title' => $cat)
            ));
            if(!empty($category)){
                $news = $this->Tiding->getNewsOnCategory($category['TidCategory']['id']);
            }
        }

        if(empty($news)){
            $news = '';
            $error = 'Not Found';
        }else{
            $error = '';
        }

        $this->set(array(
            'news' => $news,
            'error' => $error
        ));
    }

    public function tag()
    {

        if (!empty($this->request->query['tag'])) {
            $cat = urldecode($this->request->query['tag']);
            $category = $this->Tag->find('first', array(
                'conditions' => array('Tag.title' => $cat)
            ));

            if(!empty($category)){
                $news = $this->Tiding->getNewsOnTag($category['Tag']['id']);
            }
        }

        if(empty($news)){
            $news = '';
            $error = 'Not Found';
        }else{
            $error = '';
        }

        $this->set(array(
            'news' => $news,
            'error' => $error
        ));
//        $active_sort = 'last';
//        if (isset($this->request->params['pass'][0])) {
//            $tag = urldecode($this->request->params['pass'][0]);
//
//
//            $this->paginate = array(
//                'limit' => BLOG_MAIN_PAGINATE,
//                'conditions' => [
//                    'Tiding.active' => 1
//                ],
//                'joins'=>array(
//                    array(
//                        'type'=>'inner',
//                        'table'=>'tags_tidings',
//                        'alias'=>'TidingTags',
//                        'conditions'=>array(
//                            'TidingTags.tiding_id = Tiding.id',
//                        )
//                    ),
//                    array(
//                        'type'=>'inner',
//                        'table'=>'tags',
//                        'alias'=>'Tags',
//                        'conditions'=>array(
//                            'TidingTags.tag_id = Tags.id',
//                            'Tags.title'=> $tag // your criteria for tag id = 33
//                        )
//
//                    )
//                ),
//                'order' => array(
//                    'Tiding.created' => 'DESC'
//                ),
//                'group' => [
//                    'Tiding.id'
//                ]
//            );
//        }
//
//        $this->Paginator->settings = $this->paginate;
//        $news = $this->Paginator->paginate('Tiding');
//        if (!(boolean)$news) {
//            $this->redirect('/blog');
//        }
//        $this->set(array(
//            'news' => $news,
//            'active_sort' => $active_sort,
//        ));
//
//        if ($this->request->is('ajax')) {
//            $this->render('ajax', 'ajax'); // View, Layout
//        } else{ $this->render('index');}
    }

    public function search()
    {
        if (!isset($this->request->query['searchWord']) && strlen($this->request->query['searchWord']) <= 0) {
            $this->redirect('/news');
        }else{

        }

        $word = strip_tags($this->request->query['searchWord']);
        $tag = urldecode($word);
        $this->Paginator->settings = $this->paginate;
        $news = $this->Paginator->paginate('Tiding', array('Tiding.active' => 1,
            'Tiding.title LIKE "%' . $tag . '%" or Tiding.content LIKE "%' . $tag . '%" or Tiding.full_content LIKE "%' . $tag . '%"'
        ));
        if (!(boolean)$news) {
            $error = 'Not found';
        } else {
            $error = null;
        }

        $this->set(array(
            'news' => $news,
            'query_s' => $tag,
            'error' => $error
        ));

        if ($this->request->is('ajax')) {
            $this->render('ajax', 'ajax'); // View, Layout
        } else {
            $this->render('index');
        }

    }

    public function like($id)
    {
        if ($this->request->params['isAjax']) {
            if (!is_numeric($id)) {
                throw new NotFoundException();
            }

            if (!isset($_COOKIE['post_' . $id])) {
                $result = $this->Tiding->updateAll(
                    array('Tiding.likes' => 'Tiding.likes + 1'),
                    array('Tiding.id' => $id)
                );
                if ($result) {
                    setcookie('post_' . $id, $id);
                }

                $data = $this->Tiding->find('first', array(
                    'conditions' => array('Tiding.id' => $id),
                ));
                exit(json_encode(array(
                    'error' => false,
                    'msg_title' => 'Your like is accepted',
                    'msg' => 'Thanks',
                    'likes' => $data['Tiding']['likes']
                )));
            } else {
                exit(json_encode(array(
                    'error' => false,
                    'msg_title' => 'Your likes is do not accept',
                    'msg' => 'Thanks! But you try like this post again -_-',
                )));
            }

        } else {
            $this->redirect($this->referer());
        }
        exit;
    }

    public function add_comment(){
        $this->autoRender = false;

        $err        = false;
        $err_msg    = '';
        $validation = array();

        if($this->data != null){
            $this->TidComment->set($this->data);
            if($this->TidComment->validates()){
                if($this->TidComment->save()){
//                    $email = ClassRegistry::init('Option')->GetOptionByName('email');
//
//                    $this->Email->to 		= $email[0]['Option']['value'];
//                    $this->Email->subject 	= "You've got new comment";
//                    $this->Email->from 		= $this->data['TidComment']['email'];
//                    $this->Email->sendAs 	= 'both';
//                    $this->Email->smtpOptions = array(
//                        'port'=>'465',
//                        'timeout'=>'30',
//                        'auth' => true,
//                        'host' => 'ssl://smtp.gmail.com',
//                        'username'=>'vt.api.test@gmail.com',
//                        'password'=>'tusxvslffjlcplfa',
//                    );
//                    $this->Email->delivery = 'smtp';
//                    $this->Email->send(('<p>Dear Admin,</p>
//                    <p>You got new comment in our <a href="' . env('SERVER_NAME') . '" target="_blank">system.</a></p>
//                    <p>Subject: New comment</p>
//                    <p>Thanks and have a nice day!</p>'));
                }
                else
                {
                    $err        = true;
                    $err_msg    = 'Error! Cant`t save comment.';
                }
            }else{
                $err        = true;
                $err_msg    = 'Error! Not valid data.';
                $validate = $this->validateErrors($this->TidComment);

                foreach($validate as $key => $value)
                    $validation[$key] = $value[0];
            }
        }else{
            $err = true;
            $err_msg = 'Error! No data for processing.';
        }
        $response = array(
            'err'        => $err,
            'err_msg'    => $err_msg,
            'validation' => json_encode($validation)
        );
        return json_encode($response);
    }
}

