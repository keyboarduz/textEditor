<?php
declare(strict_types=1);

use DI\ContainerBuilder;

function merge($a, $b)
{
    $args = func_get_args();
    $res = array_shift($args);
    while (!empty($args)) {
        foreach (array_shift($args) as $k => $v) {
            if (is_int($k)) {
                if (array_key_exists($k, $res)) {
                    $res[] = $v;
                } else {
                    $res[$k] = $v;
                }
            } elseif (is_array($v) && isset($res[$k]) && is_array($res[$k])) {
                $res[$k] = merge($res[$k], $v);
            } else {
                $res[$k] = $v;
            }
        }
    }

    return $res;
}

return function (ContainerBuilder $containerBuilder) {
    // Global Settings Object
    $containerBuilder->addDefinitions(
        merge(
            require (__DIR__ . '/settings.common.php'),
            require (__DIR__ . '/settings.local.php')
        )
    );
};
