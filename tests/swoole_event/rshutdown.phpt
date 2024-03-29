--TEST--
swoole_event: read stdin
--SKIPIF--
<?php require __DIR__ . '/../include/skipif.inc'; ?>
--FILE--
<?php declare(strict_types = 1);
require __DIR__ . '/../include/bootstrap.php';

error_reporting(E_ALL & E_DEPRECATED);

swoole_event_add(STDIN, function ($fp) {
    var_dump(fread($fp, 1024));
    swoole_event_del(STDIN);
});

swoole_timer_after(100, function () {
    swoole_event_del(STDIN);
    fclose(STDIN);
});

?>
--EXPECTF--
