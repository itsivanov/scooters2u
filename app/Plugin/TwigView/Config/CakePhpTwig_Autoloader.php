<?php
class CakePhpTwig_Autoloader
{
    static public function register($pathToTwigLibs = null)
    {
        spl_autoload_register(array(new self, 'autoload'));

        self::registerTwigAutoloader($pathToTwigLibs);

        require_once 'bootstrapable.php';
    }

    static public function registerTwigAutoloader($pathToTwigLibs = null)
    {
        if (is_null($pathToTwigLibs)) {
            App::import('vendor', 'TwigView.Autoloader',
                        array('file' => 'Twig' . DS . 'lib'. DS . 'Twig' . DS . 'Autoloader.php'));
        } else {
            require $pathToTwigLibs;
        }

        Twig_Autoloader::register();
    }

    static public function autoload($class)
    {
        if (0 === strpos($class, 'Twig')) {
            $file = str_replace('_', '/', $class) . '.php';
        } elseif ($class == 'CakePhpTwig_Bootstrap') {
            $file = 'bootstrap.php';
        } else {
            return;
        }

        $userLibsDir = APP . "Lib" . DS . "CakePhpTwig" . DS;
        if (file_exists($userLibsDir . $file)) {
            require $userLibsDir . $file;
        }
    }
}