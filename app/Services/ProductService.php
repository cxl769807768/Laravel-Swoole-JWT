<?php
namespace App\Services;
use App\Product;

class ProductService{
    public function list($params){
        $query = Product::query();
        $params['tid'] && $query->where('tid','=',$params['tid']);
        $params['name'] && $query->where('name','like',$params['name'].'%');
        $params['status'] && $query->where('status','=',$params['status']);
        //$query->toSql();查看生成的SQL
        return $query->get();
    }
}