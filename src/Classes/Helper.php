<?php
namespace App\Classes;

class Helper 
{
    const LIMIT = 2;
    public static function GetNames(string $name)
    {
        $names = explode(" ", $name);
        $j = 0;
        $inner = [];
        $arr = [];
        foreach ($names as $n) {
            if ($j == Helper::LIMIT)
            {
                array_push($arr, $inner);
                $inner = [];
                array_push($inner, $n);
                $j = 1;
            }
            else
            {
                array_push($inner, $n);
                $j++;
            }
        }
        if (!empty($inner))
        {
            array_push($arr, $inner);
        }
        return $arr;
    }
    public static function GetMaxSizeOfName(array $array)
    {
        $maxSize = 0;
        $name = '';
        foreach ($array as $i => $value) {
            $n = strlen(implode(" ", $value));
            if ($maxSize <= $n)
            {
                $maxSize = $n;
                $name = implode(" ", $value);
            }
        }
        return $name;
    }
}