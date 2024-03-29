--TEST--
swoole_table: key-value operate
--SKIPIF--
<?php require  __DIR__ . '/../include/skipif.inc'; ?>
--FILE--
<?php declare(strict_types = 1);
require __DIR__ . '/../include/bootstrap.php';

const PI =  3.1415926;
const NAME = 'rango';

$table = new swoole_table(65536);

$table->column('id', swoole_table::TYPE_INT);
$table->column('name', swoole_table::TYPE_STRING, 128);
$table->column('num', swoole_table::TYPE_FLOAT);

if (!$table->create())
{
    echo __LINE__." error";
}
if (!$table->set('test_key', array('id' => 1, 'name' => NAME, 'num' =>PI)))
{
    echo __LINE__." error";
}

$ret = $table->get('test_key');
if (!($ret and is_array($ret) and $ret['id'] == 1))
{
    echo __LINE__." error";
}

$ret = $table->get('test_key', 'id');
if (!($ret and $ret == 1))
{
    echo __LINE__." error";
}

Assert::eq($table->get('test_key', 'name'), NAME);
Assert::eq($table->get('test_key', 'num'), PI);

// field not exists
Assert::false($table->get('test_key', 'id_no_exists'));
Assert::false($table->get('test_key_no_exists', 'id_no_exists'));

$ret = $table->exists('test_key');
if (!($ret))
{
    echo __LINE__." error";
}

$ret = $table->exists('test_key_not_exists');
if ($ret)
{
    echo __LINE__." error";
}

$ret = $table->incr('test_key','id', 2);
if (!$ret)
{
    echo __LINE__." error";
}
$_value = $table->get('test_key', 'id');
if ($_value != 3)
{
    echo __LINE__." error";
}

$ret = $table->decr('test_key', 'id', 2);
if (!$ret)
{
    echo __LINE__ . " error";
}
$_value = $table->get('test_key', 'id');
if ($_value != 1)
{
    echo __LINE__ . " error";
}

$table->set('hello_world', array('id' => 100, 'name' => 'xinhua', 'num' => 399.66));
if (count($table) != 2)
{
    echo __LINE__." error";
}

$ret =  $table->del('test_key');
if (!$ret)
{
    echo __LINE__." error";
}
if ($table->exists('test_key'))
{
    echo __LINE__." error";
}
echo "SUCCESS\n";
?>
--EXPECT--
SUCCESS
