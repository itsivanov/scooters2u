<?php

/**
 * Created by PhpStorm.
 * User: root
 * Date: 26.07.16
 * Time: 16:24
 */
class InnerSlideAttachment extends AppModel
{
    public $belongsTo = ['InnerSlide'];

    public function getAllAttachmentsWithNameKeys($attachments = null) {
        if(empty($attachments)) {
            $attachments = $this->find('all');
        }

        if(!empty($attachments)) {
            foreach ($attachments as $key => $value) {
                if(isset($value))
                    $attachments[$value['name']] = $value;
            }
        }

        return $attachments;
    }
}