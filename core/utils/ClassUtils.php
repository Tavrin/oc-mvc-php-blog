<?php


namespace Core\utils;


class ClassUtils
{
    public static function getClassNameFromObject($object) {
        return substr(strrchr(get_class($object), '\\'), 1);
    }
}