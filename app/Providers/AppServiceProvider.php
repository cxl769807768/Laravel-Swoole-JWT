<?php

namespace App\Providers;

use App\Services\Common\LengthAwarePaginatorService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        /*框架分页数据返回改写，服务注册，暂时没用到
        $this->app->bind('Illuminate\Pagination\LengthAwarePaginator',function ($app,$options){
            return new LengthAwarePaginatorService($options['items'], $options['total'], $options['perPage'], $options['currentPage'] , $options['options']);
        });
        **/
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
