<?php
namespace App\Services\Common;
/**
 * 重写分页返回数组
 * Class LengthAwarePaginatorService
 * @package App\Services\Common
 */
class LengthAwarePaginatorService extends \Illuminate\Pagination\LengthAwarePaginator
{
    public function toArray()
    {
        return [
            'list' => $this->items->toArray(),
            'total' => $this->total(),
        ];
    }
}