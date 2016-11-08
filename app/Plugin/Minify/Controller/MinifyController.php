<?php
/**
 * Minify Controller
 *
 * @package	Minify.Controller
 */
class MinifyController extends Controller {

    /**
     * @param $type
     */
    public function index($type) {
		if (!empty($this->request->base)) {
			$this->_adjustFilenames();
		}

		App::import('Vendor', 'Minify.minify/min/index');

		$this->response->statusCode('304');
		exit();
	}

	private function _adjustFilenames() {
		$baseUrl = $this->request->base;
		$baseLen = strlen($baseUrl);
		$files = explode(',', $_GET['f']);
		foreach ($files as &$file) {
			if (!strncmp($file, $baseUrl, $baseLen)) {
				$file = substr($file, $baseLen);
			}
		}
		$_GET['f'] = implode(',', $files);
	}
}