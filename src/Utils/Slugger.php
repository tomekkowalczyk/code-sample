<?php

namespace App\Utils;

class Slugger
{
    /**
     * @param string $text
     *
     * @return false|string|string[]|null
     */
    public static function sluggify(string $text)
    {
        $text = preg_replace('/\s+/', '-', mb_strtolower(trim(strip_tags($text)), 'UTF-8'));

        if (empty($text)) {
            return null;
        }

        return $text;
    }
}
