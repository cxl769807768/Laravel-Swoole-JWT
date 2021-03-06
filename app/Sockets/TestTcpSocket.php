<?php
namespace App\Sockets;
use Hhxsv5\LaravelS\Swoole\Socket\TcpSocket;
use Illuminate\Support\Facades\Log;
use Swoole\Server;

//创建Socket处理类，继承Hhxsv5\LaravelS\Swoole\Socket\{TcpSocket|UdpSocket|Http|WebSocket}
class TestTcpSocket extends TcpSocket
{

    public function onConnect(Server $server, $fd, $reactorId)
    {
        Log::info('New TCP connection', [$fd]);
        $server->send($fd, 'Welcome to LaravelS Is Task?'. var_dump($server->taskworker));
    }
    public function onReceive(Server $server, $fd, $reactorId, $data)
    {
        Log::info('Received data', [$fd, $data]);
        $server->send($fd, 'LaravelS: ' . $data);
        if ($data === "quit\r\n") {
            $server->send($fd, 'LaravelS: bye' . PHP_EOL);
            $server->close($fd);
        }
    }

    public function onClose(Server $server, $fd, $reactorId)
    {
        Log::info('Close TCP connection', [$fd]);
        $server->send($fd, 'Goodbye');
    }
}