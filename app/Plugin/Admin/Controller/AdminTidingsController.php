<?php
App::uses('AdminAppController', 'Admin.Controller');

/**
 * Pages Controller
 * @property AdminPage $AdminPage
 */
class AdminTidingsController extends AdminAppController
{
    public $uses = array('Tiding','TidCategory','Tag','TagsTiding','TidComment');

    public $paginate = array(
        'limit' => 10,
        'order' => array(
            'Tiding.id' => 'asc'
        )
    );

    const ACTIVE = 1;
    const INACTIVE = 0;

    public function beforeFilter()
    {
        parent::beforeFilter();
    }

    public function index()
    {
        $this->setActiveMenu(array('blog'));

        $this->set(array(
            'list' => $this->Tiding->find('all')
        ));
    }

    public function add()
    {
        $this->setActiveMenu(array('blog'));

        $this->setActiveMenu(array('news'));
        if ($this->request->data) {
            $data = $this->request->data;
            if($this->Tiding->save($data['Tiding'])){

                //save tags
                $tid_tags = [];
                $i = 0;
                foreach ($data['tag_tidings'] as $item){
                    if (isset( $item['tag_id'])){
                        $tid_tags[$i]['tag_id'] = $item['tag_id'];
                        $tid_tags[$i]['tiding_id'] = $this->Tiding->getLastInsertID();
                    }
                $i++;
                }

                //save categories
                $tid_cat = [];
                $i = 0;
                foreach ($data['tid_on_category'] as $item){
                    if (isset( $item['category_id'])){
                        $tid_cat[$i]['category_id'] = $item['category_id'];
                        $tid_cat[$i]['tiding_id'] = $this->Tiding->getLastInsertID();
                    }
                    $i++;
                }
                $this->loadModel("TidsOnCategory");

                if($this->TagsTiding->saveAll($tid_tags) && $this->TidsOnCategory->saveAll($tid_cat)){
                    $this->setFlash('News is saved', 'success');
                    $this->redirect(array('action' => 'index'));
                }

            } else {
                $errors = $this->Tiding->validationErrors;
                $this->setFlash('News is not saved', 'error');
            }

        }

        $this->set([
            'tags' => $tags = $this->Tag->find('all', [
                'conditions'=> [
                    'active' => 1
                ]
            ]),
            'tidcategories' => $this->TidCategory->getAllActiveForNews()
        ]);

    }

    public function edit($id)
    {
        $this->setActiveMenu(array('blog'));

        if ($this->request->data) {
            $data = $this->request->data;
            $this->loadModel("TagsTiding");
            $this->loadModel("TidsOnCategory");

            foreach ($data['tag_tidings'] as $teg_tiding) {
                if (empty($teg_tiding['tag_id']) && !empty($teg_tiding['id'])){
                    $this->TagsTiding->delete($teg_tiding);
                }
            }

            $this->TagsTiding->saveAll($data['tag_tidings']);


            foreach ($data['tid_on_category'] as $teg_tiding) {
                if (empty($teg_tiding['category_id']) && !empty($teg_tiding['id'])){
                    $this->TidsOnCategory->delete($teg_tiding);
                }
            }
            $this->TidsOnCategory->saveAll($data['tid_on_category']);



            if (isset($data['Tiding']['title']) && strlen($data['Tiding']['title']) > 0) {
                $data['Tiding']['slug'] = strtolower(Inflector::slug($data['Tiding']['title'], '-'));
            }

            $this->Tiding->id = $id;

            if ($this->Tiding->save($data))
            {
                $this->setFlash('News is saved', 'success');
            } else {
                $errors = $this->Tiding->validationErrors;
                $this->setFlash('News is not saved', 'error');
            }
        }

        $data = $this->Tiding->read('', $id);
        $this->request->data = $data;

        $this->set(array(
            'tidcategories' => $this->TidCategory->getAllActiveForNews(),
            'title'=> 'Edit',
            'tags' => $tags = $this->Tag->find('all', [
                'conditions'=> [
                    'active' => 1
                ]
            ]),
            'checkedTag' =>  $this->Tiding->getTagOnIdNews($id),
            'news_id' => $id,
            'checkedCat' => $this->Tiding->getTidOnId($id)

    ));

        $this->render('add');
    }

    public function activate($id)
    {
        $this->Tiding->id = $id;
        $page = $this->Tiding->read('active');
        if ($page['Tiding']['active'] == self::ACTIVE) {
            $this->Tiding->saveField('active', self::INACTIVE);
            $this->setFlash('News is blocked', 'success');
        } else {
            $this->Tiding->saveField('active', self::ACTIVE);
            $this->setFlash('News is active', 'success');
        }
        $this->redirect(array('action' => 'index'));
    }

    public function delete($id)
    {
        if ($this->Tiding->delete($id)) {
            $this->setFlash('News is deleted', 'success');
        } else {
            $this->setFlash('News is not deleted', 'error');
        }
        $this->redirect(array('action' => 'index'));
    }

    public function comments($id)
    {

        $this->set(array(
            'list' => $this->TidComment->find('all',array("conditions"=>array("tiding_id"=>$id))),
        ));
//        $this->render("comments");
    }

    public function delete_comment($id)
    {
        if ($this->TidComment->delete($id)) {
            $this->setFlash('Comment is deleted', 'success');
        } else {
            $this->setFlash('Comment is not deleted', 'error');
        }

        $this->redirect($_SERVER[HTTP_REFERER]);
    }

    public function activate_comments($id)
    {
        $this->TidComment->id = $id;
        $page = $this->TidComment->read('active');
        if ($page['TidComment']['active'] == self::ACTIVE) {
            $this->TidComment->saveField('active', self::INACTIVE);
            $this->setFlash('Comment is blocked', 'success');
        } else {
            $this->TidComment->saveField('active', self::ACTIVE);
            $this->setFlash('Comment is active', 'success');
        }
        $this->redirect($_SERVER[HTTP_REFERER]);
    }

}
