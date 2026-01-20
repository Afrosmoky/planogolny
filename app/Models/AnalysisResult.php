<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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

    public function analysis()
    {
        return $this->belongsTo(Analysis::class, 'analyses_id');
    }
}
