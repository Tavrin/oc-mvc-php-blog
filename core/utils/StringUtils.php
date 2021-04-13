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

    public static function slugify(string $str)
    {
        $separator = '-';
        $accents_regex = '~&([a-z]{1,2})(?:acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i';
        $special_cases = array( '&' => 'and', "'" => '');
        $str = mb_strtolower( trim( $str ), 'UTF-8' );
        $str = str_replace( array_keys($special_cases), array_values( $special_cases), $str );
        $str = preg_replace( $accents_regex, '$1', htmlentities( $str, ENT_QUOTES, 'UTF-8' ) );
        $str = preg_replace("/[^a-z0-9]/u", "$separator", $str);
        return preg_replace("/[$separator]+/u", "$separator", $str);
    }

}