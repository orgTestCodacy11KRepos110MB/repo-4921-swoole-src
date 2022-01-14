--TEST--
swoole_coroutine/exception: error
--SKIPIF--
<?php require  __DIR__ . '/../../include/skipif.inc'; ?>
--FILE--
<?php declare(strict_types = 1);
require __DIR__ . '/../../include/bootstrap.php';
go(function () {
    echo "start\n";
    throw new Exception('coro Exception');
    co::usleep(1000);
    echo "after sleep\n";
});
echo "end\n";
?>
--EXPECTF--
start

Fatal error: Uncaught Exception: coro Exception %s
Stack trace:
%A
  thrown in %s/tests/swoole_coroutine/exception/error.php on line 5
