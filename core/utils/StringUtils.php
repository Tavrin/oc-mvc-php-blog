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
        $string = (string)trim($string);
        if (empty($string)) {
            return "";
        }

        if (!preg_match("/[^0-9(.|,)]+/",$string)) {
            if (preg_match("/([.]|[,])+/", $string)) {
                $string =  (double)$string;
            } else {
                $string = (int)$string;
            }
        }

        if ('true' === $string) {
            $string = true;
        }

        if ('false' === $string) {
            $string = false;
        }

        return $string;
    }

}