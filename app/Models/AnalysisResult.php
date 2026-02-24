<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class AnalysisResult extends Model
{
    protected $table = 'analyses_results';
    protected $fillable = [
        'analyses_id',
        'surroundings',
        'legal_constraints',
        'gus_data',
        'meta',
        'generated_at',
    ];

    protected $casts = [
        'surroundings' => 'array',
        'legal_constraints' => 'array',
        'gus_data' => 'array',
        'meta' => 'array',
        'generated_at' => 'datetime',
    ];

    public function analysis(): BelongsTo
    {
        return $this->belongsTo(Analysis::class, 'analyses_id');
    }
}
