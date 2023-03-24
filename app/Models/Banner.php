<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Banner extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'image',
        'url',
    ];

    protected function image(): Attribute{
        return Attribute::make(
            get: fn ($image) => asset('/storage/posts/' .$image),
        );
    }
}
