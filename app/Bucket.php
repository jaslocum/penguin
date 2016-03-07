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

        if ($bucket_rec->save()){

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

        } else {

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
            }

            if( isset($description) ) {

                return $description;

            } else {

                return null;

            }

        }

    }

}
