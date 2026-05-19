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

        if (strpos($num, '.') !== false) {
            $num = explode('.', $num)[0]; // Remove decimal part
        }

        return preg_replace('/(?<=\d)(?=(\d{2})+(?:\d)(?!\d))/', ',', $num);
    }

    /**
     * Format number to Indian format without decimals (e.g. 2600000 -> 26,00,000)
     *
     * @param int|float|string $num
     * @return string
     */
    public static function formatIndianPriceWithoutDecimals($num)
    {
        if (empty($num)) {
            return '0';
        }

        $num = (string) $num;
        $num = preg_replace('/,/', '', $num);

        if (strpos($num, '.') !== false) {
            $num = explode('.', $num)[0]; // Remove decimal part
        }

        return preg_replace('/(?<=\d)(?=(\d{2})+(?:\d)(?!\d))/', ',', $num);
    }
}
