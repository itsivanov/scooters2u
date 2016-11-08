<?php
App::uses('AdminAppController', 'Admin.Controller');

/**
 * Feedbacks Controller
 *
 * @property Feedback $Feedback
 * @property ClaimRequest $ClaimRequest
 *
 */
class AdminFeedbacksController extends AdminAppController {
    public $uses = array('Feedback');
    public $components = array('Admin.DataTables');
    public $helpers = array('Admin.DataTables');

    const ACTIVE   = 1;
    const INACTIVE = 0;

    public function index()
    {
        $settings  = [
            'limit' => 15,
            'order' => ['id' => 'desc'],
            'contain' => array(
                'User',
                'User.UserInfo',
            ),
        ];

        $this->Paginator->settings = $settings;

        $list = $this->Paginator->paginate('Feedback');
        $this->set('list', $list);

//        var_dump($list);die();
    }

    public function export()
    {
        // send response headers to the browser
        header( 'Content-Type: text/csv' );
        header( 'Content-Disposition: attachment;filename=feedbacks.csv');
        $fp = fopen('php://output', 'w');

        fputcsv($fp, array(
            'User name',
            'Email',
            'Created',
            'Like',
            'Improve',
            'comment',
        ));

        $settings  = [
            'order' => ['Feedback.id' => 'desc'],
            'contain' => array(
                'User',
                'User.UserInfo',
            ),
        ];

        $list = $this->Feedback->find('all', $settings);

        foreach ($list as $item) {

            $name = (!empty($item['User']['UserInfo']['first_name']) ? $item['User']['UserInfo']['first_name'] : '')
                . ' '
                . (!empty($item['User']['UserInfo']['last_name']) ? $item['User']['UserInfo']['last_name'] : '');

            fputcsv($fp, array(
                $name,
                $item['User']['email'],
                (new DateTime($item['Feedback']['created']))->format('m/d/Y'),
                $item['Feedback']['like'],
                $item['Feedback']['improve'],
                $item['Feedback']['comment'],
            ));

        }

        fclose($fp);
        die();
    }

//
//    public function view($id) {
//        $this->Feedback->id = $id;
//        $data = $this->Feedback->read();
//        $this->Feedback->saveField('viewed', 1);
//        $this->set('contact', $data);
//    }

    public function delete($id) {
        if ($this->Feedback->delete($id)) {
            $this->setFlash('Feedback is deleted', 'success');
        } else {
            $this->setFlash('Feedback is not deleted', 'error');
        }
        $this->redirect($this->request->referer());
    }
}
