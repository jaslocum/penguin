<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{

    protected $fillable = array('category','category_rec_id');

    public function category()
    {
        return $this->belongsTo('App\Category');
    }

}
