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

    static public function getAltFilename($request){

        // set alt_image if passed as a header or url parameter
        if(isset($request->alt_image)) {

            return $request->alt_image;

        } else {

            $alt_image = $request->header('alt_image');

            $parameter_mark = strpos($alt_image,";");

            if ($parameter_mark){

                $alt_image = substr($alt_image,0,$parameter_mark);

            } else {

                $first_chr = substr($alt_image,0,1);

                if($first_chr==";"){
                    $alt_image="";
                }

            }

            if( isset($alt_image) ) {

                return $alt_image;

            } else {

                return null;

            }

        }

    }

}
