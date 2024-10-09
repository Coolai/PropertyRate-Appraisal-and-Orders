<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;


class Appraiser extends Model
{
    use HasFactory;

    /**
     * Get the zip code details associated with the appraiser.
     */
    public function zipCodeDetails(): HasOne
    {
        return $this->hasOne(ZipCode::class, 'zip', 'zip_code');
    }
}
