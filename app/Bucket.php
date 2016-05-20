<?php

namespace App;

use App\Category;
use Illuminate\Database\Eloquent\Model;

class Bucket extends Model
{

    /**
     * A Category can have many images.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */

    protected $fillable = array();

    protected $table = 'bucket';

    public function images()
    {
        return $this->hasMany('App\Image');
    }

    public function category()
    {
        return $this->hasOne('App\Category');
    }

    static public function newBucket($category, $key=null, $description = "")
    {

        //create bucket record
        $bucket_rec = new Bucket;
        $category_rec = Category::where(compact('category'))->first();

        if (!isset($category_rec)){
            $category_rec = Category::newCategory($category);
            if(!isset($category_rec)){
                return;
            }
        }

        $bucket_rec->category_id = $category_rec->id;

        if(isset($description)){
            $bucket_rec->description = $description;
        }

        if ($bucket_rec->save()) {

            if(isset($key)) {
                $bucket_rec->key = $key;
            }else{
                $bucket_rec->key = $bucket_rec->id;
            }

            if ($bucket_rec->save()) {
                return $bucket_rec;
            }else{
                return null;
            }

        }else{

            return null;
        }

    }

    /**
     * @param $bucket_rec
     * @return null
     */
    static public function updateBucket($bucket_rec)
    {

        //update image record
        if($bucket_rec->save()) {
            return $bucket_rec;
        } else {
            return null;
        }

    }

    /**
     * @param $category
     * @param $key
     * @return null
     */
    static public function getBucket($category, $key)
    {
        // find category and key key pair
        $category_rec = Category::where(compact('category'))->first();
        if (isset($category_rec)) {
            $category_id = $category_rec->id;
            $bucket_rec = Bucket::where(compact('category_id', 'key'))->first();
            if(isset($bucket_rec)){
                return $bucket_rec;
            } else {
                return null;
            }
        } else {
            return null;
        }
    }

    /**
     * @param $id
     * @return null
     */
    static public function getBucketById($id)
    {
        $bucket_rec = Bucket::where(compact('id'))->first();
        if(isset($bucket_rec)){
            return $bucket_rec;
        } else {
            return null;
        }
    }

    /**
     * @param $id
     * @return null
     */
    static public function getCategoryRec($id)
    {
        // get bucket
        $bucket_rec = Bucket::where(compact('id'))->first();

        if (isset($bucket_rec)) {

            $category_id = $bucket_rec->category_id;

            // find category and key key pair
            $category_rec = Category::where(['id'=>compact('category_id')])->first();

            if (isset($category_rec)) {

                return $category_rec;

            } else {

                return null;

            }

        } else {

            return null;

        }

    }

    /**
     * @param $id
     * @return null
     */
    static public function getCategoryName($id)
    {
        // get bucket
        $bucket_rec = Bucket::where(compact('id'))->first();

        if (isset($bucket_rec)) {

            $category_id = $bucket_rec->category_id;

            // find category and key key pair
            $category_rec = Category::where(['id'=>compact('category_id')])->first();

            if (isset($category_rec)) {

                return $category_rec->category;

            } else {

                return null;

            }

        } else {

            return null;

        }

    }

    static public function getDescription($request){

        // set description if passed as a header or url parameter
        if(isset($request->description)) {

            return $request->description;

        } else {

            $description = $request->header('description');
            $parameter_mark = strpos($description,";");

            if ($parameter_mark){

                $description = substr($description,0,$parameter_mark);

            } else {

                $first_chr = substr($description,0,1);

                if($first_chr==";"){
                    $description="";
                }

            }

            if( isset($description) ) {

                return $description;

            } else {

                return null;

            }

        }

    }

    static public function getAltCategory($request){

        // set alt_category if passed as a header or url parameter
        if(isset($request->alt_category)) {

            return $request->alt_category;

        } else {

            $alt_category = $request->header('alt_category');

            $parameter_mark = strpos($alt_category,";");

            if ($parameter_mark){

                $alt_category = substr($alt_category,0,$parameter_mark);

            } else {

                $first_chr = substr($alt_category,0,1);

                if($first_chr==";"){
                    $alt_category="";
                }

            }

            if( isset($alt_category) ) {

                return $alt_category;

            } else {

                return null;

            }

        }

    }

    static public function getAltKey($request){

        // set alt_key if passed as a header or url parameter
        if(isset($request->alt_key)) {

            return $request->alt_key;

        } else {

            $alt_key = $request->header('alt_key');

            $parameter_mark = strpos($alt_key,";");

            if ($parameter_mark){

                $alt_key = substr($alt_key,0,$parameter_mark);

            } else {

                $first_chr = substr($alt_key,0,1);

                if($first_chr==";"){
                    $alt_key="";
                }

            }

            if( isset($alt_key) ) {

                return $alt_key;

            } else {

                return null;

            }

        }

    }

    static public function getAltFilename($request){

        // set alt_filename if passed as a header or url parameter
        if(isset($request->alt_filename)) {

            return $request->alt_filename;

        } else {

            $alt_filename = $request->header('alt_filename');

            $parameter_mark = strpos($alt_filename,";");

            if ($parameter_mark){

                $alt_filename = substr($alt_filename,0,$parameter_mark);

            } else {

                $first_chr = substr($alt_filename,0,1);

                if($first_chr==";"){
                    $alt_filename="";
                }

            }

            if( isset($alt_filename) ) {

                return $alt_filename;

            } else {

                return null;

            }

        }

    }

}
