<?php

namespace SMTC\Silex\Service;

class UserManager
{
    private $userFactory;

    public function __construct($userFactory)
    {
        $this->userFactory = $userFactory;
    }

    public function findAll()
    {
        return $this->userFactory
            ->select_many_expr('firstName', 'lastName', array('addresses' => "group_concat(a.street, '|')"))
            ->table_alias('u')
            ->join('address', array('u.id', '=', 'a.user_id'), 'a')
            ->group_by('u.id')
            ->find_many();
    }
}