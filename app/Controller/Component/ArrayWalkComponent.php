<?php

/**
 * Class VideoComponent
 *
 * @property AppController $Controller
 *
 */
class ArrayWalkComponent extends Component
{

    public $name = 'ArrayWalk';

    static public function renameArrayKey($new_name, $old_name, $array = array())
    {

        if (is_array($array) && count($array) > 0) {

            foreach ($array as $key => $value) {
                if (is_array($value) && count($value) > 0) {

                    $array[$key] = self::renameArrayKey($new_name, $old_name, $value);

                }
                if (mb_stristr($key, $old_name) && is_array($value)) {
                    $temp = $array[$key];
                    unset($array[$key]);
                    $array[$new_name] = $temp;
                }
            }
            return $array;
        }
        return $array;
    }
}

?>