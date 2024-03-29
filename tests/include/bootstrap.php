<?php
/**
 * This file is part of Open Swoole
 *
 * @link     https://openswoole.com
 * @contact  hello@openswoole.com
 * @license  https://github.com/openswoole/library/blob/master/LICENSE
 */

require_once __DIR__ . '/config.php'; // (`once` because it may required in skip when we run phpt)
require  __DIR__ . '/../../tools/bootstrap.php';

// PHP settings
error_reporting(E_ALL ^ E_DEPRECATED);
ini_set('memory_limit', '1024M');
assert_options(ASSERT_ACTIVE, 1);
assert_options(ASSERT_WARNING, 1);
assert_options(ASSERT_BAIL, 0);

// Swoole settings
OpenSwoole\Util::setAio([
    'socket_dontwait' => 1,
    'disable_dns_cache' => true,
    'dns_lookup_random' => true,
]);
Co::set([
    'socket_timeout' => 5
]);
if (empty(getenv('SWOOLE_DEBUG'))) {
    Co::set([
        'log_level' => SWOOLE_LOG_INFO,
        'trace_flags' => 0,
        'enable_deadlock_check' => false,
    ]);
}

// Components
require __DIR__ . '/lib/vendor/autoload.php';

class_alias(SwooleTest\ProcessManager::class, ProcessManager::class);
class_alias(SwooleTest\ServerManager::class, ServerManager::class);
class_alias(SwooleTest\RandStr::class, RandStr::class);
class_alias(SwooleTest\TcpStat::class, TcpStat::class);

function swoole_array_default_value(array $array, $key, $default_value = null)
{
    return array_key_exists($key, $array) ? $array[$key] : $default_value;
}

class Assert extends SwooleTest\Assert
{
    protected static $throwException = false;
}
