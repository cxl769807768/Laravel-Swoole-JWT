<?php

namespace App;

use Encore\Admin\Grid\Model;
use Encore\Admin\Traits\DefaultDatetimeFormat;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Log;

class Product extends BaseModel
{
    use DefaultDatetimeFormat;
    use Notifiable;
    public $table = "product";

    public function __construct()
    {

    }

    protected $fillable = [
        'name', 'cover', 'phone', 'status', 'tid'
    ];
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     * 多对一的关系
     */
    public function productTid()
    {
        return $this->belongsTo(ModType::class,'tid','id');
    }
    public function setSlideshowAttribute($value)
    {
        if (is_array($value)) {
            $this->attributes['slideshow'] = json_encode($value);
        }
    }

    public function getSlideshowAttribute($value)
    {
        if(!empty($value)){
            return json_decode($value, true);

        }
    }
    public function getCoverAttribute($value)
    {
        return $this->attributes['cover'] = empty($value) ? '' : config('filesystems.disks.admin.url')."/".$value;

    }
    public function getList($params){
        $query = self::newQuery();
        if(isset($params['tid']) && !empty($params['tid'])) $query->where('tid','=',$params['tid']);
        if(isset($params['name']) && !empty($params['name']))  $query->where('name','like',$params['name'].'%');
        if(isset($params['status']) && !empty($params['status']))  $query->where('status','=',$params['status']);
        if(isset($params['page']) && !empty($params['page'])){
            return $query->paginate($params['pageSize'], ['*'],  'page',$params['page']);

        }else{
            return $query->take(5)->get();
        }

    }
}
