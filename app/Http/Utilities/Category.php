<?php

namespace App\Http\Utilities;

class Category
{

    protected static $categories =
    [
        "work order image"              => "woimg",
        "work order process"            => "woprc",
        "work order docs"               => "wodoc",
        "part image"                    => "ptimg",
        "part process"                  => "ptprc",
        "packing list signature"        => "plsig",
    ];

    public static function all()
    {
        return static::$categories;
    }

}