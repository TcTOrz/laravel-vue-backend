<?php

namespace App\Repositories\Contracts;

interface BaseRepositoryInterface {

    /**
     * @param $id
     * @param array $columns
     * @return mixed
     */
    public function find($id, $columns = array('*'));

    /**
     * @return mixed
     */
    public function models();
}
