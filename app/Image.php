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
    static public function newImage($bucket_id = null)
    {

        $image_rec = new Image;

        if (isset($bucket_id)) {

            $image_rec->bucket_id = $bucket_id;

        }

        if($image_rec->save()) {

            if(isset($image_rec)) {

                $image_rec->md5 = md5($image_rec->id);

                if($image_rec->save()) {

                    return $image_rec;

                }else{

                    return null;

                }


            }else {

               return null;

            }

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
