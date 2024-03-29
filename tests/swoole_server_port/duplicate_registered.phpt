--TEST--
swoole_server_port: duplicate registered
--SKIPIF--
<?php require __DIR__ . '/../include/skipif.inc'; ?>
--FILE--
<?php declare(strict_types = 1);
require __DIR__ . '/../include/bootstrap.php';
$server = new OpenSwoole\Server('127.0.0.1');
$server->on('receive', function () { });
Assert::same(true, !!'load Assert');
$mem = null;
for ($n = 1000; $n--;) {
    $server->on('receive', function () { });
    if ($mem === null) {
        $mem = memory_get_usage();
    }
    Assert::same(memory_get_usage(), $mem);
}
echo "DONE\n";
?>
--EXPECT--
DONE
