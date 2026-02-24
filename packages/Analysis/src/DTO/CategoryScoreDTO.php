<?php

namespace Planogolny\Analysis\DTO;

final readonly class CategoryScoreDTO
{
    public function __construct(
        public string $category,
        public float $score
    ) {}
}
