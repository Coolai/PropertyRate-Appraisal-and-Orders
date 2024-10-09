<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class ZipCode extends Model
{
    use HasFactory;

    protected function latitude(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => rtrim($value, "0"),
        );
    }
    
    protected function longitude(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => rtrim($value, "0"),
        );
    }
}
