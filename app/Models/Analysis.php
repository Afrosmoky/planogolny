<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

final class Analysis extends Model
{
    protected $fillable = [
        'address',
        'lat',
        'lng',
        'status',
    ];
}
