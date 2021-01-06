<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Encore\Admin\Traits\DefaultDatetimeFormat;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use DefaultDatetimeFormat;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'mobile', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
        //若有多个JWT验证（如admin验证），则区分
        //return ['role'=>'user'];
    }
    /**
     * @param $value
     * 生成密码
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }
    /**
     * 更新token
     * @return mixed|string
     */
    public function generateToken() {
        $this->api_token = str_random(128);
        $this->save();
        return $this->api_token;
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
