<?php

namespace App\Repositories\Eloquent;

abstract class BaseRepository {
    /**
     * TODO
     */
    public function find($id, $columns = array('*')) {

    }

    /**
     * @return mixed
     */
    public function models() {
        return $this->model;
    }
}
