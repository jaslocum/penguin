<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category_definition extends Model
{
    /**
     * A Category_definition can have many buckets.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */

    protected $fillable = array('category','description','mime','max_size_MB');

    public function categories()
    {
        return $this->hasMany('App\Category');
    }

}
