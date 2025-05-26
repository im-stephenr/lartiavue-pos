<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    //
    protected $fillable = ['cashReceive','change','total','orderDetails','cashier'];

    protected $appends = ['created_at_formatted','updated_at_formatted', 'total_formatted'];

    public function getCreatedAtFormattedAttribute()
    {
        return $this->created_at?->format("Y-m-d H:i");
    }

    public function getUpdatedAtFormattedAttribute()
    {
        return $this->updated_at?->format("Y-m-d H:i");
    }

    public function getTotalFormattedAttribute()
    {
        return $this->total = number_format($this->total, 2);
    }
}
