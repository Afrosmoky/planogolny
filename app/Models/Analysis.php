<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

final class Analysis extends Model
{
    protected $fillable = [
        'address',
        'lat',
        'lng',
        'status',
        'session_id'
    ];

    public function result(): HasOne
    {
        return $this->hasOne(AnalysisResult::class, 'analyses_id', 'id');
    }
}
