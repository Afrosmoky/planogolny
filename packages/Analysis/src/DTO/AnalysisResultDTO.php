<?php

namespace Planogolny\Analysis\DTO;

final readonly class AnalysisResultDTO
{
    public function __construct(
        public array $categories,
        public array $raw = []
    ) {}
}
