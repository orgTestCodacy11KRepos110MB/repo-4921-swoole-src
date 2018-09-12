--TEST--
swoole_websocket_server: websocket server disconnect with neither code nor reason
--SKIPIF--
<?php require __DIR__ . '/../include/skipif.inc'; ?>
--FILE--
<?php
require_once __DIR__ . '/../include/bootstrap.php';
include __DIR__ . "/../include/lib/class.websocket_client.php";
$pm = new ProcessManager;
$pm->parentFunc = function (int $pid) use ($pm) {
    go(function () use ($pm) {
        for ($i = 100; $i--;) {
            $cli = new \Swoole\Coroutine\Http\Client('127.0.0.1', $pm->getFreePort());
            $cli->set(['timeout' => 1]);
            $ret = $cli->upgrade('/');
            assert($ret);
            $cli->push('shutdown');
            $frame = $cli->recv();
            assert($frame->opcode = WEBSOCKET_OPCODE_CLOSE);
            $close_code = unpack('n', substr($frame->data, 0, 2))[1];
            $close_reason = substr($frame->data, 2);
            assert(md5($close_code) === $close_reason);
            assert($cli->recv() === false);
            assert($cli->errCode === 0); // connection close normally
        }
    });
    swoole_event_wait();
    $pm->kill();
};
$pm->childFunc = function () use ($pm) {
    $serv = new swoole_websocket_server('127.0.0.1', $pm->getFreePort());
    $serv->set([
        'worker_num' => 1,
        'log_file' => '/dev/null'
    ]);
    $serv->on('WorkerStart', function () use ($pm) {
        $pm->wakeup();
    });
    $serv->on('Message', function (swoole_websocket_server $serv, swoole_websocket_frame $frame) {
        if ($frame->data == 'shutdown') {
            $code = mt_rand(0, 5000);
            $reason = md5($code);
            if (mt_rand(0, 1)) {
                $close_frame = new swoole_websocket_close_frame;
                $close_frame->code = $code;
                $close_frame->reason = $reason;
                $serv->push($frame->fd, $close_frame);
            } else {
                $serv->disconnect($frame->fd, $code, $reason);
            }
        }
    });
    $serv->start();
};
$pm->childFirst();
$pm->run();
?>
--EXPECT--
