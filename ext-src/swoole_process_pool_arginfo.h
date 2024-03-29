/* This is a generated file, edit the .stub.php file instead.
 * Stub hash: 851c48efa1749bc93c6356be21748ad3de073fd7 */

ZEND_BEGIN_ARG_INFO_EX(arginfo_class_Swoole_Process_Pool___construct, 0, 0, 1)
ZEND_ARG_TYPE_INFO(0, workerNum, IS_LONG, 0)
ZEND_ARG_TYPE_INFO_WITH_DEFAULT_VALUE(0, ipcType, IS_LONG, 0, "OpenSwoole\\Process\\Pool::IPC_NONE")
ZEND_ARG_TYPE_INFO_WITH_DEFAULT_VALUE(0, msgqueue_key, IS_LONG, 0, "0")
ZEND_ARG_TYPE_INFO_WITH_DEFAULT_VALUE(0, enableCoroutine, _IS_BOOL, 0, "false")
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Swoole_Process_Pool_set, 0, 1, _IS_BOOL, 1)
ZEND_ARG_TYPE_INFO(0, settings, IS_ARRAY, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Swoole_Process_Pool_on, 0, 2, _IS_BOOL, 0)
ZEND_ARG_TYPE_INFO(0, event, IS_STRING, 0)
ZEND_ARG_TYPE_INFO(0, callback, IS_CALLABLE, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Swoole_Process_Pool_listen, 0, 1, _IS_BOOL, 0)
ZEND_ARG_TYPE_INFO(0, host, IS_STRING, 0)
ZEND_ARG_TYPE_INFO_WITH_DEFAULT_VALUE(0, port, IS_LONG, 0, "0")
ZEND_ARG_TYPE_INFO_WITH_DEFAULT_VALUE(0, backlog, IS_LONG, 0, "2048")
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Swoole_Process_Pool_write, 0, 1, _IS_BOOL, 0)
ZEND_ARG_TYPE_INFO(0, data, IS_STRING, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Swoole_Process_Pool_detach, 0, 0, _IS_BOOL, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_TYPE_MASK_EX(
    arginfo_class_Swoole_Process_Pool_getProcess, 0, 0, OpenSwoole\\Process, MAY_BE_FALSE)
ZEND_ARG_TYPE_INFO_WITH_DEFAULT_VALUE(0, workerId, IS_LONG, 0, "-1")
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Swoole_Process_Pool_start, 0, 0, _IS_BOOL, 1)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Swoole_Process_Pool_stop, 0, 0, IS_VOID, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Swoole_Process_Pool_shutdown arginfo_class_Swoole_Process_Pool_detach

ZEND_BEGIN_ARG_INFO_EX(arginfo_class_Swoole_Process_Pool___destruct, 0, 0, 0)
ZEND_END_ARG_INFO()
