--TEST--
swoole_http_server: issue 2360 (swoole_http_server silently fails to read requests)
--SKIPIF--
<?php require __DIR__ . '/../include/skipif.inc'; ?>
--FILE--
<?php declare(strict_types = 1);
require __DIR__ . '/../include/bootstrap.php';
define('SOCKET_BUFFER_SIZE', 2 << mt_rand(10, 13)); // 1024 ~ 8192
phpt_echo("SOCKET_BUFFER_SIZE=" . SOCKET_BUFFER_SIZE . PHP_EOL);
$pm = new ProcessManager();
$pm->setRandomFunc(function () {
    $size = mt_rand(SOCKET_BUFFER_SIZE, SOCKET_BUFFER_SIZE << 3); // 1024 ~ 65536
    $data = '';
    for ($i = 0; $i < $size; $i++) {
        $data .= sprintf('%01x', $i % 16);
    }
    return $data;
});
$pm->initRandomDataEx(1, 8);
$pm->parentFunc = function () use ($pm) {
    co::run(function () use ($pm) {
        $cli = new OpenSwoole\Coroutine\Http\Client('127.0.0.1', $pm->getFreePort());
        $cli->set(['socket_buffer_size' => SOCKET_BUFFER_SIZE]);
        for ($n = 8; $n--;) {
            $data = $pm->getRandomData();
            Assert::true($cli->post('/', $data));
            Assert::same($cli->statusCode, 200);
            Assert::same($cli->body, $data);
            phpt_echo("posting " . strlen($data) . " bytes\n");
        }
        $cli->close();
    });
    $pm->kill();
    echo "DONE\n";
};
$pm->childFunc = function () use ($pm) {
    $server = new OpenSwoole\Http\Server('127.0.0.1', $pm->getFreePort());
    $server->set([
        'log_file' => '/dev/null',
        'socket_buffer_size' => SOCKET_BUFFER_SIZE
    ]);
    $server->on('workerStart', function () use ($pm) {
        $pm->wakeup();
    });
    $server->on('request', function (OpenSwoole\Http\Request $request, OpenSwoole\Http\Response $response) use ($pm) {
        phpt_echo("received {$request->header['content-length']} bytes\n");
        if (Assert::assert($request->rawContent() === $pm->getRandomData())) {
            $response->end($request->rawContent());
        }
    });
    $server->start();
};
$pm->childFirst();
$pm->run();
?>
--EXPECT--
DONE
