--TEST--
swoole_server: kill user process [SWOOLE_PROCESS]
--SKIPIF--
<?php
require __DIR__ . '/../include/skipif.inc';
skip_if_darwin();
//skip_if_in_valgrind();
skip("TODOv22");
?>
--FILE--
<?php declare(strict_types = 1);
require __DIR__ . "/../include/bootstrap.php";
use Swoole\Server;
const WORKER_PROC_NAME = 'swoole_unittest_server_user_process';
$pm = new SwooleTest\ProcessManager;

$pm->parentFunc = function ($pid) use ($pm) {
    for ($i = 0; $i < 1; $i++)
    {
        //杀死进程
        kill_process_by_name(WORKER_PROC_NAME);
        usleep(10000);
        //判断进程是否存在
        Assert::assert(get_process_pid_by_name(WORKER_PROC_NAME) > 0);
    }
    $pm->kill();
};

$pm->childFunc = function () use ($pm)
{
    $serv = new Server('127.0.0.1', $pm->getFreePort(), SWOOLE_PROCESS);
//    $serv->set(
//        ['log_file' => TEST_LOG_FILE,]
//    );
    $process2 = new swoole_process(function ($worker) use ($serv, $pm) {
        global $argv;
        \OpenSwoole\Util::setProcessName(WORKER_PROC_NAME);
        swoole_process::signal(SIGTERM, function () {
            swoole_event_exit();
        });
        swoole_timer_after(200000, function ($interval) use ($worker, $serv) {
            echo "OK\n";
        });
        $pm->wakeup();
    }, false);
    $serv->addProcess($process2);
    $serv->set(["worker_num" => 2, 'log_file' => '/dev/null',]);
    $serv->on("WorkerStart", function (Server $serv, $worker_id) use ($pm) {

    });
    $serv->on("Receive", function (Server $serv, $fd, $reactorId, $data)
    {
    });
    $serv->start();
};

$pm->childFirst();
$pm->run();

?>
--EXPECT--
