<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Encore\Admin\Traits\DefaultDatetimeFormat;
class Bill extends Authenticatable
{
    use DefaultDatetimeFormat;
    use Notifiable;
    public $table = "bill";
    protected $fillable = [
        'money','type'
    ];
    protected $hidden = [

    ];
    public function billType()
    {
        return $this->belongsTo(BillType::class,'type','id');
    }
}
