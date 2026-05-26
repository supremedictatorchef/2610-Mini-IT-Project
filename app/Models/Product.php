<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['club_id','title','description','price','image','stock'];

    public function club()
    {
        return $this->belongsTo(Club::class);
    }
}
