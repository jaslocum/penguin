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

}
