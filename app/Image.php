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

}
