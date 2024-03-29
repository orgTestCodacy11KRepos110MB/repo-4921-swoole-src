--TEST--
swoole_server/object: pipe message
--SKIPIF--
<?php
require __DIR__ . '/../../include/skipif.inc';
?>
--FILE--
<?php declare(strict_types = 1);
require __DIR__ . '/../../include/bootstrap.php';

use Swoole\Server;
use Swoole\Server\Packet;
use Swoole\Server\PipeMessage;


$pm = new SwooleTest\ProcessManager;

$pm->parentFunc = function ($pid) use ($pm) {
    co::run(function () use ($pm) {
        $client = new OpenSwoole\Coroutine\Client(SWOOLE_SOCK_UDP);
        if (!$client->connect('127.0.0.1', $pm->getFreePort())) {
            echo "Over flow. errno=" . $client->errCode;
            die("\n");
        }

        $data = base64_encode(random_bytes(rand(1024, 8192))) . "\r\n\r\n";;
        $client->send($data);
        $recv_data = $client->recv();
        Assert::eq($recv_data, $data);
    });
    $pm->kill();
};

$pm->childFunc = function () use ($pm) {
    $serv = new Server('127.0.0.1', $pm->getFreePort(), SWOOLE_PROCESS, SWOOLE_SOCK_UDP);
    $serv->set(
        array(
            "worker_num" => 2,
            'event_object' => true,
            'log_file' => '/dev/null',
        )
    );
    $serv->on(
        'WorkerStart',
        function (Server $serv) use ($pm) {
            $pm->wakeup();
        }
    );

    $serv->on('pipeMessage', function (Server $serv, PipeMessage $msg) {
        Assert::eq($msg->worker_id, 1 - $serv->getWorkerId());
        $object = $msg->data;
        $serv->sendto($object->address, $object->port, $object->data, $object->server_socket);
    });

    $serv->on(
        'packet',
        function (Server $serv, Packet $object) {
            $serv->sendMessage($object, 1 - $serv->getWorkerId());
        }
    );
    $serv->start();
};

$pm->childFirst();
$pm->run();
?>
--EXPECT--
