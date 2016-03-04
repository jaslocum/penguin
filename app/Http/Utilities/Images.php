<?php

namespace App\Http\Utilities;

use App\Image;
use Illuminate\Database\Eloquent\Model;

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

}