<?php
App::uses('AppHelper', 'View/Helper');

class jGrowlHelper extends AppHelper
{
    public $helpers = array('Html');

    public function flash($key = 'flash')
    {
        if(CakeSession::check('FlashMessage.' . $key)) {
			$flash = CakeSession::read('FlashMessage.' . $key);

			$this->Html->scriptStart();
			foreach ($flash as $flashData) {
				$params = array(
                    'title' => ($flashData['title']) ? $flashData['title'] : '',
                    'text' => $flashData['message'],
                    'image' => '/admin/img/icons/'.$flashData['type'].'-big.png',
					'time' => 3500,
                    'delay' => 0,
                    'speed' => 400,
                    'effect' => 'slidein',
                    'sticky' => false,
                    'position' => 'right-top',
                    'className' => '',
                    'closable' => true
				);

				if($flashData['type'] == 'error') {
					$params['sticky'] = true;
				}

                echo '$(function() { $.e_notify.growl(' . json_encode($params) . '); });';

			}
			$out = $this->Html->scriptEnd();

            CakeSession::delete('FlashMessage.' . $key);

			return $out;
		}

        return '';
    }
}