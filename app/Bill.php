<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Encore\Admin\Traits\DefaultDatetimeFormat;
class Bill extends BaseModel
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
