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

    static public function newBucket($category, $key, $description = "")
    {

        //create bucket record
        $bucket = new Bucket;
        $category_rec = Category::where(compact('category'))->first();
        if (!isset($category_rec)){
            $category_rec = Category::newCategory($category);
            if(!isset($category_rec)){
                return;
            }
        }

        $bucket->category_id = $category_rec->id;
        $bucket->key = $key;
        $bucket->description = $description;

        if ($bucket->save()){
            return $bucket;
        } else {
            return;
        }

    }

    /**
     * @param $category
     * @param $key
     * @return mixed
     */
    static public function getBucket($category, $key)
    {
        // find category and key key pair
        $category_rec = Category::where(compact('category'))->first();
        if (isset($category_rec)) {
            $category_id = $category_rec->id;
            $bucket = Bucket::where(compact('category_id', 'key'))->first();
            return $bucket;
        } else {
            return null;
        }
    }

}
