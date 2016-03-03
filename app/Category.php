<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    /**
     * A Category_definition can have many bucket.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */

    protected $fillable = array('category','description','mime','max_size_MB');

    protected $table = "category";

    public function buckets()
    {
        return $this->hasMany('App\Bucket');
    }

    static public function newCategory($category)
    {

        $category_rec = new Category;
        $category_rec->category = $category;
        //default accepted mime types
        $category_rec->mime = 'image/jpg, image/jpeg, image.png';
        //default max file size that can be uploaded
        $category_rec->max_size_MB = 5;

        if($category_rec->save()) {
            return $category_rec;
        } else {
            return;
        }

    }

}
