--TEST--
swoole_server: close queued
--SKIPIF--
<?php
require __DIR__ . '/../include/skipif.inc';
?>
--FILE--
<?php declare(strict_types = 1);
require __DIR__ . '/../include/bootstrap.php';

use Swoole\Server;
use Swoole\Constant;

$pm = new SwooleTest\ProcessManager;

$pm->parentFunc = function ($pid) use ($pm) {
    go(function () use ($pm) {
        $client = new OpenSwoole\Coroutine\Client(SWOOLE_SOCK_TCP);
        $client->set(['socket_buffer_size' => 128*1024]);
        if (!$client->connect('127.0.0.1', $pm->getFreePort())) {
            exit("connect failed\n");
        }
        $client->send("close");
        Co::sleep(1);
        $data = '';

        while(true) {
            $ret = $client->recv();
            if (empty($ret)) {
                break;
            }
            $data .= $ret;
            if (substr($ret, -2, 2) == "\r\n") {
                break;
            }
        }
        Assert::eq(strlen($data), 2*1024*1024 +2);
        echo "DONE\n";
    });
    Swoole\Event::wait();
    $pm->kill();
};

$pm->childFunc = function () use ($pm) {
    $serv = new Server('127.0.0.1', $pm->getFreePort(), SERVER_MODE_RANDOM);
    $serv->set([
        'worker_num' => 1,
        'log_file' => TEST_LOG_FILE,
        'kernel_socket_send_buffer_size' => 128*1024,
        'buffer_output_size' => 4*1024*1024,
    ]);
    $serv->on("workerStart", function ($serv) use ($pm) {
        $pm->wakeup();
    });
    $serv->on('receive', function (Server $serv, $fd, $reactor_id, $data) {
        $serv->send($fd, str_repeat('A', 2 * 1024 * 1024)."\r\n");
        $serv->close($fd);
    });
    $serv->on(Constant::EVENT_CLOSE, function (Server $serv, $fd, $reactor_id) {
    });
    $serv->start();
};

$pm->childFirst();
$pm->run();
?>
--EXPECT--
DONE
