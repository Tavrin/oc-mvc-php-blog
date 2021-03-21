<?php


namespace Core\utils;


class StringUtils
{
    public static function normalizeForComparison(...$strings): array
    {
        $newStrings = [];
        foreach ($strings as $string) {
            $string = strtolower($string);
            array_push($newStrings, $string);
        }

        return $newStrings;
    }
}