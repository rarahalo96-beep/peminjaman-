<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable = ['title', 'author', 'publisher', 'year', 'stock', 'image'];

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
