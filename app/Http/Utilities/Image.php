<?php

namespace App\Http\Utilities;

use App\Images;

class Image
{
    /**
     * @param $id
     * @return mixed
     */
    public static function image($id)
    {
        $deleted = false;
        $image = Images::where(compact('id', 'deleted'))->f();
        return $image;
    }

}