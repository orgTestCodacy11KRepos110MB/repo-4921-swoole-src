--TEST--
swoole_coroutine: dns Lookup IPv6
--SKIPIF--
<?php require __DIR__ . '/../include/skipif.inc';
skip_if_offline();
?>
--FILE--
<?php declare(strict_types = 1);
require __DIR__ . '/../include/bootstrap.php';

use Swoole\Coroutine\System;


Co::run(function () {
    $host = System::dnsLookup('www.google.com', 2, AF_INET6);
    Assert::assert(filter_var($host, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) !== false);
});
?>
--EXPECT--
