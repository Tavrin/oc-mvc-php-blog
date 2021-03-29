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

    public static function changeTypeFromValue(string $string)
    {
        $string = trim($string);
        if (empty($string)) {
            return "";
        }

        if (!preg_match("/[^0-9(.|,)]+/",$string)) {
            if (preg_match("/([.]|[,])+/", $string)) {
                return (double)$string;
            } else {
                return (int)$string;
            }
        }

        if ('true' === $string) {
            return true;
        }

        if ('false' === $string) {
            return false;
        }

        return (string)$string;
    }

}