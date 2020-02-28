<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    public function ratings() {
        return $this->hasMany('App\Rating', 'id','book_id');
    }
    public function reviews() {
        return $this->hasMany('App\Review', 'book_id','id');
    }
}
