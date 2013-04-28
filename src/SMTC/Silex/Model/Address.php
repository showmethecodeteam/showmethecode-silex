<?php

namespace SMTC\Silex\Model;

class Address extends \Model {
    public static $_table = 'address';

    public function user() {
        return $this->belongs_to('User');
    }
}