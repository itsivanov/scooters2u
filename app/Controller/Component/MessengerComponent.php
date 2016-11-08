<?php
/**
* Created by Slava Basko
* Email: basko.slava@gmail.com
* Date: 3/29/13
* Time: 12:13 PM
 *
 * @property EmailComponent $Email
*/
App::uses('Component', 'Controller');

class MessengerComponent extends Component
{

    public $name        = 'Messenger';
    public $components  = array('Email');
    public $controller;

    public function initialize(Controller $controller, $settings = array())
    {

        $this->controller =& $controller;
        $this->Email->layout = "default";
    }



    public function contactUS ($data = array(), $options = array())
    {
        $data = array_merge($data, $options);
        $emailTo = $this->controller->viewVars['options']['site_info_email'];
        $Email = new CakeEmail();
        $Email->to($emailTo);
        $Email->from(array($emailTo => 'Contact Us'));
        $Email->subject("Contact Us: {$_SERVER["SERVER_NAME"]}");
        $Email->template("contact_us");
        $Email->emailFormat("html");
        $Email->viewVars(array( 'id' => $data ));
        return $Email->send();
    }

    public function register ($data = array(), $options = array())
    {
        /** @var Option $Option */
        $data = array_merge($data, $options);
        $Option = ClassRegistry::init('Option');
        $emailTo = $Option->getByKey('email');

        $Email = new CakeEmail('default');
        $Email->to($data['User']['email']);
        $Email->from(array($emailTo => 'Products on the Go'));
        $Email->subject("Registration is complete");
        $Email->template("registration");
        $Email->emailFormat("html");
        $Email->viewVars($data);
        return $Email->send();
    }

    public function payment($to, $data = array(), $options = array())
    {
        /** @var Option $Option */
        $data = array_merge($data, $options);
        $Option = ClassRegistry::init('Option');

        $Email = new CakeEmail('default');
        $Email->to($to);
        $Email->from(array($to => 'Products on the Go'));
        $Email->subject('New order on ' . $_SERVER['SERVER_NAME']);
        $Email->template("new_notify_admin");
        $Email->emailFormat("html");
        $Email->viewVars($data);
        return $Email->send();
    }

    public function youvepay($to, $data = array(), $options = array())
    {
        /** @var Option $Option */
        $data = array_merge($data, $options);
        $Option = ClassRegistry::init('Option');
        $emailTo = $Option->getByKey('email');
        $Email = new CakeEmail('default');
        $Email->to($to);
        $Email->from(array($emailTo => 'Products on the Go'));
        $Email->subject('New order on ' . $_SERVER['SERVER_NAME']);
        $Email->template("new_notify");
        $Email->emailFormat("html");
        $Email->viewVars($data);
        return $Email->send();
    }

    public function testmail($to, $data = array(), $options = array())
    {
        /** @var Option $Option */
        $data = array_merge($data, $options);
        $emailTo = $data['email'];
        $Email = new CakeEmail('debug');
        $Email->to($to);
        $Email->from(array($emailTo => 'Products on the Go'));
        $Email->subject('New order on ' . $_SERVER['SERVER_NAME']);
        $Email->template("new_notify");
        $Email->emailFormat("html");
        $Email->viewVars($data);
        return $Email->send();
    }

    
    

}