<?php


/**
*
* @property User $User
*/


class AddUserShell extends AppShell {

    public $uses = array('User');

    public function main() {
        $this->out('########## Add User as admin ##########');
        $name = (isset($this->args[0])) ? $this->args[0] : null;
        $pass = (isset($this->args[1])) ? $this->args[1] : null;
        $this->out('Pass -> '.$pass . ' Hash -> '.Security::hash($pass, null, true));
        if (!empty($name) && !empty($pass)) {
            $data['User']['id'] = 1;
            $data['User']['username'] = $name;
            $data['User']['password'] =  $pass;
            $this->out(print_r($data, true));
            if ($this->User->save($data, false)) {
                $this->out('>> User successfully added');
            } else {
                $this->out('!!! An error occurred when saving data !!!');
            }
        } else {
            $this->out('!!! Error! No params !!!');
        }

        return true;
    }

}