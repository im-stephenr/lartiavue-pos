<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    //
    protected $fillable = ['title','price','category','status','image'];

    // This adds a new key to the categories when fetched which is the values inside $appends
     protected $appends = [
        'created_at_formatted',
        'updated_at_formatted',
    ];

    // the getXXXXAttribute is a laravel function which automatically reads the XXXX into underscore separated values
    // So for example the getCreatedAtFormattedAttribute will be automatically calls $appends created_at_formatted
    public function getCreatedAtFormattedAttribute()
    {
        return $this->created_at?->format('Y-m-d H:i');
    }

    public function getUpdatedAtFormattedAttribute()
    {
        return $this->updated_at?->format('Y-m-d H:i');
    }
}
