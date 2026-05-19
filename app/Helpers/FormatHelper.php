<?php

namespace App\Helpers;

class FormatHelper
{
    /**
     * Format number to Indian format (e.g. 2600000 -> 26,00,000)
     *
     * @param int|float|string $num
     * @return string
     */
    public static function formatIndianPrice($num)
    {
        if (empty($num)) {
            return '0';
        }

        $num = (string) $num;
        $num = preg_replace('/,/', '', $num);
        
        $dec = '';
        if (strpos($num, '.') !== false) {
            list($num, $dec) = explode('.', $num);
        }
        
        $res = preg_replace('/(?<=\d)(?=(\d{2})+(?:\d)(?!\d))/', ',', $num);
        
        if ($dec !== '') {
            $res .= '.' . $dec;
        }
        
        return $res;
    }
}
