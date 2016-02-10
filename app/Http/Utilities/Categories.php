<?php

namespace App\Http\Utilities;

use App\Category;
use Illuminate\Database\Eloquent\Model;

class Categories
{
    /**
     * @return mixed
     */
    public static function all()
    {
        $categories = Category::orderBy('category')->where('id','>','1')->get();
        return $categories;
    }

}