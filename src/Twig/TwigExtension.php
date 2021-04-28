<?php


namespace App\Twig;


use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class TwigExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('moment', [$this, 'getMoment']),
        ];
    }

    public function getMoment($datetime): string
    {
        $time = time() - strtotime($datetime);

        $units = array (
            31536000 => 'année',
            2592000 => 'mois',
            604800 => 'semaine',
            86400 => 'jour',
            3600 => 'heure',
            60 => 'minute',
            1 => 'seconde'
        );

        foreach ($units as $unit => $val) {
            if ($time < $unit) continue;
            $numberOfUnits = floor($time / $unit);
            return ($val == 'seconde')? 'il y a quelques secondes' : 'il y a  '.$numberOfUnits. ' '.$val.(($numberOfUnits>1 && $val !== 'mois') ? 's' : '').' ';
        }
    }
}