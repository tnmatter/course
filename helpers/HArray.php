<?php

namespace app\helpers;

use ArrayAccess;
use Closure;

class HArray
{
    /**
     * @param array|ArrayAccess $array
     * @param string|int|Closure $key колбек должен принимать 1 параметр - элемент массива
     *
     * @return array
     */
    public static function index(array|ArrayAccess $array, string|int|Closure $key): array
    {
        $result = [];
        foreach ($array as $item) {
            if ($key instanceof Closure) {
                $result[$key($item)] = $item;
            } else {
                $result[$item[$key]] = $item;
            }
        }
        return $result;
    }
}
