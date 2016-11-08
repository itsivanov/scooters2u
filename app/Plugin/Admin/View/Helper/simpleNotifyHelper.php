<?php
App::uses('AppHelper', 'View/Helper');

class simpleNotifyHelper extends AppHelper
{
    public $helpers = array('Html');

    public function flash($key = 'flash')
    {
        if(CakeSession::check('FlashMessage.' . $key)) {
			$flash = CakeSession::read('FlashMessage.' . $key);

			$this->Html->scriptStart();
			foreach ($flash as $flashData) {


                $params = array(
                    'text' => $flashData['message'],
                    'delay' => 2000,
                );

//                $params['text'] = '<img src=\"/admin/images/icon/shortcut/24x24/'.$flashData['type'].'.png\"/> '.$params['text'];

				if($flashData['type'] == 'error') {
					$params['sticky'] = true;
				}

                echo '$(function() { show'.ucfirst(strtolower($flashData['type'])).'("' . implode('","', $params) . '"); });';

			}
			$out = $this->Html->scriptEnd();

            CakeSession::delete('FlashMessage.' . $key);

			return $out;
		}

        return '';
    }
}