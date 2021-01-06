<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Encore\Admin\Traits\DefaultDatetimeFormat;
class BillType extends BaseModel
{
    use DefaultDatetimeFormat;
    use Notifiable;
    public $table = "bill_type";
    protected $fillable = [
        'name',
    ];
    protected $hidden = [

    ];
    public function bills()
    {
        return $this->hasMany(Bill::class,'id','type');
    }


}
