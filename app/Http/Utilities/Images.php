<?php

namespace App\Http\Utilities;

use App\Image;

class Images
{

    /**
     * @param $bucket_id
     * @return mixed
     */
    public static function images($bucket_id)
    {
        $deleted = false;
        $images = Image::orderBy('id')->where(compact('bucket_id', 'deleted'))->get();
        return $images;
    }

    /**
     * @param $id
     * @return mixed
     */
    public static function image($id)
    {
        $deleted = false;
        $image = Image::where(compact('id', 'deleted'))->get();
        return $image;
    }

}