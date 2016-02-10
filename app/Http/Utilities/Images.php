<?php

namespace App\Http\Utilities;

use App\Image;
use Illuminate\Database\Eloquent\Model;

class Images
{
    /**
     * @return mixed
     */
    public static function images($bucket_id)
    {
        $deleted = false;
        $images = Image::orderBy('filename')->where(compact('bucket_id', 'deleted'))->get();
        return $images;
    }

}