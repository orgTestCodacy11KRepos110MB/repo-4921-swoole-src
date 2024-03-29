--TEST--
swoole_http_client_coro: get twice
--SKIPIF--
<?php require __DIR__ . '/../include/skipif.inc';
skip_if_offline();
?>
--FILE--
<?php declare(strict_types = 1);
require __DIR__ . '/../include/bootstrap.php';
use Swoole\Coroutine\Http\Client;
const N = 2;

co::run(function () {
    $client = new Client('www.baidu.com', 443, true);
    $client->set(['timeout' => 5,]);
    for ($i = 0; $i < N; $i++) {
        $rand = mt_rand(100000, 999999999);
        $path = "/index.php?email=" . $rand . "@" . substr(md5((string)microtime(true)), 0, 8) . ".com";
        $result = $client->get($path);
        if (!$result) {
            var_dump("ERROR: ".$client->getStatusCode());
        }
        Assert::assert($result);
        Assert::assert($client->getStatusCode() == 200);
        $client->close();
    }
});

?>
--EXPECT--
