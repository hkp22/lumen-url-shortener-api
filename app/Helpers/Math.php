<?php

namespace App\Helpers;

class Math
{
    /**
     * The base.
     *
     * @var string
     */
    private $base = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

    /**
     * Convert from base 10 to another base.
     *
     * @param  int    $value
     * @param  int    $base
     * @return string
     */
    public function toBase($value, $base = 62)
    {
        $r = $value % $base;
        $result = $this->base[$r];
        $q = floor($value / $base);
        while ($q) {
            $r = $q % $base;
            $q = floor($q / $base);
            $result = $this->base[$r] . $result;
        }

        return $result;
    }
}
