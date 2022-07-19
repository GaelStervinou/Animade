<?php

namespace App\Helpers;

class Formalize{

    /**
     * @param null|string $date
     * @return null|string
     */
    public static function formalizeDateYearMonthDay(?string $date): ?string
    {
        if(is_null($date)){
            return null;
        }
        return date('Y-m-d', strtotime($date));
    }

    /**
     * @param null|string $date
     * @return null|string
     */
    public static function formalizeDateYearCuttedMonthDay(?string $date): ?string
    {
        if(is_null($date)){
            return null;
        }
        return date('d M, Y', strtotime($date));
    }
}