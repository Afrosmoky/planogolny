<?php

namespace Planogolny\Analysis\DTO;

final readonly class CategoryScoreDTO
{
    public function __construct(
        public string $category, // e.g. "single_family", "multi_family", etc.
        public float $score // already normalized 0–100
    ) {}
}
