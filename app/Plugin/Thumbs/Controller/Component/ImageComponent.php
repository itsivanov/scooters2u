<?php
/**
 * Load phpthumb library (http://phpthumb.gxdlabs.com)
 */
App::import('Vendor', 'Thumbs.PHPThumb', array('file' => 'PHPThumb' . DS . 'src' . DS . 'ThumbLib.inc.php'));
//App::uses('PhpThumbFactory', 'Thumbs.Vendor', array('file' => 'PHPThumb' . DS . 'src' . DS . 'ThumbLib.inc.php'));
/*App::build(array('Vendor' => array(APP . 'Vendor' . DS . 'PHPThumb')));
App::uses('Vendor', 'ThumbLib.inc');*/
/**
 * Image component class
 * @author Dmitry404
 * @uses phpthumb library (http://phpthumb.gxdlabs.com)
 * @link http://wiki.github.com/masterexploder/PHPThumb/gd-api
 */
class ImageComponent extends Component
{
	/**
	 * Return phpthumb object
	 * @param string $filename The path of file to load
	 * @param array $options
	 * @param bool $isDataStream
	 * @return object
	 */
/*    public function initialize($controller) {

        //debug($controller);
    }*/
	public function create($filename, $options = array(), $isDataStream = false)
	{
		return PhpThumbFactory::create($filename, $options, $isDataStream);
	}
}