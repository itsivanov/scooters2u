<?php
App::uses('AdminAppController', 'Admin.Controller');
/**
 *@property HomePart $HomePart
 */
class AdminHomePartsController extends AdminAppController
{

    public function index()
    {
        $this->setActiveMenu(array('content'));

        $allParts = $this->HomePart->getPartsOfAdmin();

        $this->set([
            'AllParts' => $allParts
        ]);

    }


    public function edit()
    {
        $this->setActiveMenu(array('content'));

        if($this->request->data){
            $editor = $this->request->data['SiteMenu']['content'];
            unset($this->request->data['SiteMenu']);
            $this->request->data['content'] = $editor;
            $this->HomePart->save($this->request->data);
            $this->setFlash('Service is created', 'success');

        }
        $parts = $this->HomePart->getPartsOnId($this->request->id);

        $this->set([
           'partsOnId' => $parts[0]
        ]);
    }


    public function delete($id)
    {

    }


}