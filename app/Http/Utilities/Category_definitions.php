<?php

namespace App\Http\Utilities;
use App\Category_definition;
class Category_definitions
{

    public static function all()
    {
        $category_definitions = Category_definition::all();
        return $category_definitions;
    }

}