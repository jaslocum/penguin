<?php

namespace App\Http\Utilities;
use App\Category_definition;
use Illuminate\Database\Eloquent\Model;

class Category_definitions
{
    /**
     * @return mixed
     */
    public static function all()
    {
        $category_definitions = Category_definition::orderBy('category')->get();
        return $category_definitions;
    }

}