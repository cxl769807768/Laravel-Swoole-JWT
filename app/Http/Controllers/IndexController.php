<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function index(Request $request)
    {
        //var_dump($request->query());
        return view('web/index');
    }
    public function push()
    {
        /**@var \Swoole\Http\Server $swoole */
        $swoole = app('swoole');
        // $swoole->ports：遍历所有Port对象，https://wiki.swoole.com/#/server/properties?id=ports
        $port = $swoole->ports[0]; // 获得`Swoole\Server\Port`对象
        // $fd = 1; // Port中onReceive/onMessage回调的FD
        // $swoole->send($fd, 'Send tcp message from controller to port client');
        // $swoole->push($fd, 'Send websocket message from controller to port client');
    }
}
