<?php

namespace App;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BaseModel extends Model
{
    public $timestamps = false;
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }
    public function addAll(Array $data)
    {
        $rs = DB::table($this->getTable())->insert($data);
        return $rs;
    }
}
