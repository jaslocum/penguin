<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bucket extends Model
{

    /**
     * A Category can have many images.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */

    protected $fillable = array('category','key');

    public function images()
    {
        return $this->hasMany('App\Image');
    }

    public function category_definition()
    {
        return $this->hasOne('App\Category_definition');
    }

}
