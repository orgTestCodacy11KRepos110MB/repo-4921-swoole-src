
ZEND_BEGIN_ARG_INFO_EX(arginfo_swoole_void, 0, 0, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_INFO_EX(arginfo_class_Swoole_Runtime_enableCoroutine, 0, 0, 0)
ZEND_ARG_INFO(0, enable)
ZEND_ARG_INFO(0, flags)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_INFO_EX(arginfo_class_Swoole_Runtime_setHookFlags, 0, 0, 1)
ZEND_ARG_INFO(0, flags)
ZEND_END_ARG_INFO()

#define arginfo_class_Swoole_Runtime_getHookFlags arginfo_swoole_void
