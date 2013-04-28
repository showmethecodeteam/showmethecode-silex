<?php

namespace SMTC\Silex\Model;

class User extends \Model {
    public static $_table = 'user';

    public function addresses() {
        return $this->has_many('Address');
    }
}