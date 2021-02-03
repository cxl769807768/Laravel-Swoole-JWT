<?php

namespace App\Providers;

use GuzzleHttp\Psr7\Request;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {

        parent::boot();
        Event::listen('laravels.received_request', function (\Illuminate\Http\Request $req, $app) {

//            $req->query->set('token', $req->get('authorized'));// 修改querystring
            $req->request->set('token', $req->input('token')); // Change post of request
            $req->request->set('token', $req->header('authorized')); // Change post of request

            $req->headers->set('Authorization', 'Bearer ' . $req->input('token'));
            $req->headers->set('Authorization', 'Bearer ' . $req->header('authorized'));
        });
//        Event::listen('laravels.generated_response', function (\Illuminate\Http\Request $req, \Symfony\Component\HttpFoundation\Response $rsp, $app) {
//            $rsp->headers->set('authorized', $rsp->headers->get('authorization'));// Change header of response
//        });

    }
}
