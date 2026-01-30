<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QrCode extends Model
{
    protected $fillable = ['book_id', 'code', 'title', 'description', 'type', 'data'];
    protected $casts = [
        'data' => 'array',
    ];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }
}
