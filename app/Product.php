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

    protected $fillable = [
        'name', 'subtitle','cover','slideshow', 'phone', 'status', 'tid','introduce'
    ];
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    protected $attributes = [
        'status' => 1,
        'created_at'=>1,

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
        $value = is_array($value)? $value : explode(',',$value);
        if (is_array($value)) {
            foreach ($value as $k => $v){
                $v = str_replace(env('APP_URL'),'',$v);
                //处理后台和前端上传的文件路径
                $value[$k] = strpos($v,'/uploads/')!==false ? $v : '/uploads/images/admin/'.$v;
            }
            $this->attributes['slideshow'] = serialize($value);
        }
    }

    public function getSlideshowAttribute($value)
    {
        if(!empty($value)){
            $value = unserialize($value);
            foreach ($value as $k => $v){
                $value[$k] = env('APP_URL').$v;
            }
            return $value;

        }
    }
    public function setCoverAttribute($value)
    {
        $result = empty($value) ? '' : str_replace(env('APP_URL'),'',$value);
        //处理后台和前端上传的文件路径
        return $this->attributes['cover'] = empty($result) ? '' : (strpos($result,'/uploads/')!==false ? $result : '/uploads/images/admin/'.$result);

    }
    public function getCoverAttribute($value)
    {
        return $this->attributes['cover'] = empty($value) ? '' : env('APP_URL').$value;

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
