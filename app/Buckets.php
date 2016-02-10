<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Buckets extends Model
{

    /**
     * A Category can have many images.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */

    protected $fillable = array('category','key');

    public function images()
    {
        return $this->hasMany('App\Images');
    }

    public function category()
    {
        return $this->hasOne('App\Category_definition');
    }

}
