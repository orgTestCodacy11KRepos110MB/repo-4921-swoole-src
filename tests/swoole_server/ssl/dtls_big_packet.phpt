--TEST--
swoole_server/ssl: big dtls packet
--SKIPIF--
<?php require __DIR__ . '/../../include/skipif.inc'; ?>
--FILE--
<?php declare(strict_types = 1);
require __DIR__ . '/../../include/bootstrap.php';

$pm = new SwooleTest\ProcessManager;

$pm->parentFunc = function ($pid) use ($pm) {
    $client = new swoole_client(SWOOLE_SOCK_UDP | SWOOLE_SSL, SWOOLE_SOCK_SYNC);
    if (!$client->connect('127.0.0.1', $pm->getFreePort()))
    {
        exit("connect failed\n");
    }
    //TLS max record size = 16K
    $client->send("hello world".str_repeat('A', 16000));
    Assert::same($client->recv(65535), "Swoole hello world".str_repeat('A', 16000));
    $pm->kill();
};

$pm->childFunc = function () use ($pm) {
    $serv = new swoole_server('127.0.0.1', $pm->getFreePort(), SWOOLE_BASE, SWOOLE_SOCK_UDP | SWOOLE_SSL);
    $serv->set([
        'log_file' => '/dev/null',
        'ssl_cert_file' => SSL_FILE_DIR . '/server.crt',
        'ssl_key_file' => SSL_FILE_DIR . '/server.key',
    ]);
    $serv->on("workerStart", function ($serv) use ($pm) {
        $pm->wakeup();
    });
    $serv->on('receive', function ($serv, $fd, $tid, $data) {
        $serv->send($fd, "Swoole $data");
    });
    $serv->start();
};

$pm->childFirst();
$pm->run();
?>
--EXPECT--
