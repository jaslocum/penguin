<?php

namespace App\Http\Utilities;

use App\Image;

class Image
{
    /**
     * @param $bucket_id
     * @return mixed
     */
    public static function image($id)
    {
        $deleted = false;
        $image = Image::where(compact('id', 'deleted'))->get();
        return $image;
    }

}