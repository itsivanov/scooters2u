<?php

/**
 * @property Menu $Menu
 * @property SiteMenu $SiteMenu
 * @property Page $Page
 */

class AdminMenusController extends AdminAppController {

    public $name = 'AdminMenus';

    public $uses = array('SiteMenu', 'Menu', 'Page');

    public $helpers = array('Admin.ExtTree');

    public function beforeRender() {
        $this->setHoverFlag('menus');
        $this->setActiveMenu(['menus']);
    }

    public function index()
    {

        $this->setActiveMenu(array('menus'));
        $this->set('menu', $this->Menu->find('all', ['conditions'=> ['site_menu_id' => 4, 'active' => 1]]));
    }

    public function footerRight()
    {
        $menu_get = $this->Menu->find('all', ['conditions'=> ['site_menu_id' => 5]]);
        $this->set(['menu' => $menu_get]);

    }

    public function edit($id = null) {
        $this->setActiveMenu(array('menus'));
    }

    public function menu()
    {
        $this->setActiveMenu(array('menus'));

//        $menuName = $this->SiteMenu->read("name", $id);
        $menu_get = $this->Menu->find('all');
        $id = $this->request['pass'][0];
//        var_dump($menu_get);die;

//        if(!empty($menuName)) {
//            $this->set('menu_id', $id);
//            $this->set("menuName", $menuName['SiteMenu']['name']);
//            //$this->set('static_pages', $this->Page->getAllActivePages());
//        }
        $menus = $this->Menu->get($id, false, true);

        $this->set('menu', $menu_get);

    }

    public function add_item($id = null) {

        $url = explode('/',$this->request->url);
        $actionId = $this->_getIdSiteMenu($url[1]);
        if($this->request->data) {
            if($menu_id = $this->Menu->SaveMenuItem($this->request->data)) {
                $this->request->data = null;
                $this->setFlash('Item successfully added', 'success');
                $this->redirect( '/admin/'.$url[1]);

            }
        }
        $this->setActiveMenu(array('menus'));

        $this->set('action', 'add');
        $pages = array();
        foreach ($this->Page->getAllActivePages() as $key => $val) {
            $pages['/'.$key] = $val;
        }
        $this->set(['pages' => $pages, 'actionId' => $actionId]);
        $this->set('menu_id', $id);
        $this->render('edit_item');
    }

    public function edit_item($id = null, $item_id = null) {
        $url = explode('/',$this->request->url);
        $actionId = $this->_getIdSiteMenu($url[1]);
        $id = $this->request['pass'][0];
        if($this->request->data) {
            if($id = $this->Menu->SaveMenuItem($this->request->data)) {
                $this->request->data = null;
                $this->setFlash('Item successfully edited', 'success');
                $this->redirect('/admin/'.$url[1]);
            }
        }

        $pages = [];
        foreach ($this->Page->getAllActivePages() as $key => $val) {
            $pages['/'.$key] = $val;
        }
        $item = $this->Menu->find('first', ['conditions'=> ['id'=> $id]]);
        $this->set(['item' => $item, 'pages'=> $pages, 'actionId' => $actionId]);
//        $this->request->data = $this->Menu->read(null, $item_id);
//
//        $this->set('menu_id', $id);
//        $this->render('edit_item');
    }

    public function delete_item($id = null, $item_id = null) {
        $url = explode('/',$this->request->url);

        $this->Menu->delete($this->request['pass'][0]);
        $this->setFlash('Remove done', 'success');
        $this->redirect('/admin/'.$url[1]);
    }

    public function _getIdSiteMenu($key)
    {
        $this->loadModel('SiteMenu');
        $action =  $this->SiteMenu->find('first', ['conditions'=> ['key' => $key]]);
        return $action['SiteMenu']['id'];

    }

}