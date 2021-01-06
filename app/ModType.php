<?php

namespace App;


use Illuminate\Support\Facades\Log;

class ModType extends BaseModel
{

    public $table = "mod_type";
    protected $fillable = [
        'name','icon','isShow','status','pid'
    ];
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    protected $hidden = [

    ];
    public function products()
    {
        return $this->hasMany(Product::class,'id','tid');
    }
    public function parent()
    {
        return $this->hasOne(get_class($this), $this->getKeyName(), 'pid');
    }
    /**可获得表单提交过来的字段名**/
    public static function boot(){
        parent::boot();
        static::saving(function($model){
            Log::debug(print_r($model->attributes,true));
        });
    }
}
