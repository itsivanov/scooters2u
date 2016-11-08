    <?php

class Menu extends AppModel
{
    public $actsAs = array('Tree', 'Containable');

    private $_cacheName = 'DynamicMenu';


    public function beforeSave($options = array())
    {
        if (isset($this->data['Menu']['url'])){
            $this->data['Menu']['key'] = str_replace('/', '',  $this->data['Menu']['url']);
        }

        return parent::beforeSave($options);
    }

    /**
     * Return menu
     * @param $id
     * @param bool $active
     * @param bool $invalidateCache
     * @return array
     */
    public function get($id, $active=true, $invalidateCache = true)
    {
        if($invalidateCache) {
            /** @noinspection PhpDynamicAsStaticMethodCallInspection */
            Cache::delete($this->_cacheName);
        }
        $conditions = array('site_menu_id' => $id);
        if ($active) {
            $conditions = array_merge($conditions, array('active' => true));
        }

        /** @noinspection PhpDynamicAsStaticMethodCallInspection */
        $data = Cache::read($this->_cacheName);
        if($data === false) {
            $this->contain();
            $data = $this->find('threaded',
                array(
                    'conditions' => $conditions,
                    'fields' => array('id', 'parent_id', 'lft', 'rght', 'name', 'key', 'active', "url"),
                    'order' => "lft"
                )
            );
            /** @noinspection PhpDynamicAsStaticMethodCallInspection */
            Cache::write($this->_cacheName, $data);
        }

        return $data;
    }

    public function afterSave($created) {
        Cache::delete('menu_header');
        Cache::delete('menu_footer');
    }

    public function SaveMenuItem($data) {
        if(empty($data['Menu']['url'])) {
            $data['Menu']['url'] = $data['Menu']['page'];
        }

        if(empty($data['Menu']['key'])) {
            $data['Menu']['key'] = str_replace('/', '', $data['Menu']['page']);
        }

        //if($this->validate) {
            if(!$this->save($data)) {
                return false;
            }
        //}

        return $data['Menu']['site_menu_id'];
    }

}