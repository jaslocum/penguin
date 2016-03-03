<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{

    protected $fillable = array('description');

    protected $table = 'image';

    public function category()
    {
        return $this->belongsTo('App\Category');
    }

    /**
     * @return Images|null
     */
    static public function newImage()
    {

        $image_rec = new Image;
        if($image_rec->save()) {
            return $image_rec;
        } else {
            return null;
        }

    }

    /**
     * @param $image_rec
     * @return null
     */
    static public function updateImage($image_rec)
    {

        //update image record
        if($image_rec->save()) {
            return $image_rec;
        } else {
            return null;
        }

    }


}
