<?php

namespace App\Contracts\Repositories;

interface BookUserRepository extends AbstractRepository
{
    public function getData($data = [], $with = [], $dataSelect = ['*']);
}
